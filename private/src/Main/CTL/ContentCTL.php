<?php
/**
 * Created by PhpStorm.
 * User: NUIZ
 * Date: 9/2/2558
 * Time: 16:49
 */

namespace Main\CTL;
use Main\DB\Medoo\MedooFactory;
use Main\Helper\ArrayHelper;
use Main\Helper\ResponseHelper;
use Main\Helper\URL;
use Main\DAO\ListDAO;
use Main\Http\FileUpload;
use Main\Permission\AccountPermission;
use Main\View\JsonView;

/**
 * @Restful
 * @uri /content
 */
class ContentCTL extends BaseCTL {
    private $table = "content", $table_book = "content_book", $table_video = "content_video", $table_attach_file = "content_attach_file";
    private $join = [
//        "[>]content_video"=> ["content_id"=> "content_id"],
        "[>]content_book"=> ["content_id"=> "content_id"],
        "[>]category"=> ["category_id"=> "category_id"]
    ];

    /**
     * @GET
     */
    public function gets(){
        $params = $this->reqInfo->params();
        $params["url"] = URL::absolute("/content");
        $params["join"] = $this->join;
        $params["where"] = [
            "ORDER"=> "content.content_id DESC"
        ];
        $params["field"]= ["*", "content.content_id"];

        if(isset($params["category_id"]) && !empty($params["category_id"])){
            $params["where"]["content.category_id"] = $params["category_id"];
        }

        $listResponse = ListDAO::gets($this->table, $params);
        $this->builds($listResponse["data"]);
        return new JsonView($listResponse);
    }

    /**
     * @GET
     * @uri /by_category
     */
    public function getsByCategory(){
        $params = $this->reqInfo->params();
        $params["url"] = URL::absolute("/content/by_category");
        $params["join"] = $this->join;
        $params["where"] = [
            "ORDER"=> "content.content_id DESC"
        ];
        $params["field"]= ["*", "content.content_id"];

        if(isset($params["category_id"]) && !empty($params["category_id"])){
            $catId = $params["category_id"];
            $db = MedooFactory::getInstance();

            $cat = $db->get("category", "*", ["category_id"=> $catId]);
            if(!$cat){
                return false;
            }

            if($cat['parent_id']==0){
                $loop = 2;
            }
            else {
                $loop = 1;
            }

            $allCatsId = [$catId];
            $catsId = [$catId];
            $i = 0;
            while($i < $loop){
                $items = $db->select("category", "*", ["parent_id"=> $catsId]);

                $catsId = [];
                if(count($items)==0){
                    break;
                }
                foreach($items as $value){
                    $allCatsId[] = $value['category_id'];
                    $catsId[] = $value['category_id'];
                }
                $i++;
            }

            $params["where"]["content.category_id"] = $allCatsId;
        }

        $listResponse = ListDAO::gets($this->table, $params);
        $this->builds($listResponse["data"]);
        return new JsonView($listResponse);
    }

    /**
     * @POST
     */
    public function add(){
        $params = $this->reqInfo->params();
        $insert = ArrayHelper::filterKey(["content_name", "category_id", "content_description", "content_type"], $params);

        $user = $this->reqInfo->getAuthAccount();
//        AccountPermission::requirePermission($user, [AccountPermission::ID_CLUSTER_IT, AccountPermission::ID_SUPER_ADMIN, AccountPermission::ID_WRITER]);

        // begin transaction
        $db = MedooFactory::getInstance();
        $db->pdo->beginTransaction();

        $insert["account_id"] = $user["account_id"];

//        $insert["created_at"] = date('Y-m-d H:i:s');
//        $insert["updated_at"] = $insert["created_at"];

        $insert["created_at"] = time();
        $insert["updated_at"] = $insert["created_at"];

        $id = $db->insert($this->table, $insert);

        if($insert["content_type"] == "book"){
            $insertBook = ArrayHelper::filterKey(["book_author", "book_publishing_house", "book_date", "book_type_id", "book_places"], $params);
            $insertBook["content_id"] = $id;

            $book = FileUpload::load($this->reqInfo->file("book"));
            $name = $book->generateName(true);
            $des = "public/book/".$name;
            $book->move($des);
            $insertBook['book_path'] = $name;

            $book_cover = FileUpload::load($this->reqInfo->file("book_cover"));
            $name = $book_cover->generateName(true);
            $des = "public/book_cover/".$name;
            $book_cover->move($des);
            $insertBook['book_cover_path'] = $name;

            if(isset($insertBook['book_places'])){
                $insertBook['(JSON)book_places'] = $insertBook['book_places'];
                unset($insertBook['book_places']);
            }

            $db->insert($this->table_book, $insertBook);
            $db->pdo->commit();
        }

        if($insert["content_type"] == "video"){
            function insertVideo($video, $thumb, $video_name, $id, $db, $table_video){
                $seq = $db->max($table_video, "seq", ["content_id"=> $id]);
                $seq += 1;

                $insertVideo = ["content_id"=> $id, "video_name"=> $video_name, "seq"=> $seq];
                $video = FileUpload::load($video);
                $name = $video->generateName(true);
                $des = "public/video/".$name;
                $video->move($des);
                $insertVideo['video_path'] = $name;

                $video_thumb = FileUpload::load($thumb);
                $name = $video_thumb->generateName(true);
                $des = "public/video_thumb/".$name;
                $video_thumb->move($des);
                $insertVideo['video_thumb_path'] = $name;

                $db->insert($table_video, $insertVideo);
            }

            $cVideo = count($_FILES["videos"]["name"]);
            if($cVideo != count($_FILES["videos_thumb"]["name"]) || $cVideo != count($params['videos_name'])){
                return ResponseHelper::error("Video and name,thumb length not equals");
            }

//            $videos = [];
            foreach($_FILES["videos"]["name"] as $key=> $value){
//                $videos[] = ["name"=> $_FILES["videos"]["name"][$key], "tmp_name"=> $_FILES["videos"]["tmp_name"][$key]];
                insertVideo(["name"=> $_FILES["videos"]["name"][$key], "tmp_name"=> $_FILES["videos"]["tmp_name"][$key]],
                    ["name"=> $_FILES["videos_thumb"]["name"][$key], "tmp_name"=> $_FILES["videos_thumb"]["tmp_name"][$key]],
                    $params['videos_name'][$key]
                    , $id, $db, $this->table_video);
            }

            $db->pdo->commit();
        }

        function insertAttachFile($file, $id, $db, $table_attach_file){
            $insertVideo = ["content_id"=> $id];
            $file = FileUpload::load($file);
            $name = $file->generateName(true);
            $des = "public/attach_file/".$name;
            $file->move($des);
            $insertVideo['file_path'] = $name;
            $insertVideo['file_name'] = $file->getOriginalName();

            $db->insert($table_attach_file, $insertVideo);
        }

        if(isset($_FILES["attach_files"])
            && count($_FILES["attach_files"]["name"]) > 0
            && !empty($files['kmcenter_map_pic']['tmp_name']))
        {
            foreach($_FILES["attach_files"]["name"] as $key=> $value){
                insertAttachFile(["name"=> $_FILES["attach_files"]["name"][$key], "tmp_name"=> $_FILES["attach_files"]["tmp_name"][$key]],
                    $id, $db, $this->table_attach_file);
            }
        }

        $item = $this->_get($id);
        return new JsonView($item);
    }

    /**
     * @GET
     * @uri /[i:id]
     */
    public function get(){
        $id = $this->reqInfo->urlParam('id');

        $item = $this->_get($id);

        if(!$item){
            $item = null;
        }

        return new JsonView($item);
    }

    /**
     * @DELETE
     * @uri /[i:id]
     */
    public function delete(){
        $id = $this->reqInfo->urlParam("id");
        $old = $this->_get($id);
        $db = MedooFactory::getInstance();
        $db->pdo->beginTransaction();
        if($old["content_type"]=="book"){
            $db->delete($this->table, ["content_id"=> $id]);
            $db->delete($this->table_book, ["content_id"=> $id]);
            $db->pdo->commit();
            return ["success"=> true];
        }
        else if($old["content_type"]=="video"){
            $db->delete($this->table, ["content_id"=> $id]);
            $db->delete($this->table_video, ["content_id"=> $id]);
            $db->pdo->commit();
            return ["success"=> true];
        }
        else {
            return ResponseHelper::error("content type not support");
        }
    }

    /**
     * @PUT
     * @uri /[i:id]
     */
    public function edit(){
        $type = $this->reqInfo->param("content_type");
        if($type == "book"){
            return $this->_editBook();
        }
        else if($type == "video"){
            return $this->_editVideo();
        }
        else {
            return ResponseHelper::error("Only type book, video");
        }
    }

    public function _editVideo(){
        $id = $this->reqInfo->urlParam('id');
        $params = $this->reqInfo->params();

//        $old = $this->_get($id);

        $update = ArrayHelper::filterKey(["content_name", "category_id", "content_description"], $params);
        if(count($update) > 0){
//            $update["updated_at"] = date("Y-m-d H:i:s");
            $update["updated_at"] = time();
        }

        $db = MedooFactory::getInstance();

        $db->update($this->table, $update, ["content_id"=> $id]);

        return $this->_get($id);
    }

    public function _editBook(){
        $id = $this->reqInfo->urlParam('id');
        $params = $this->reqInfo->params();
        $files = $this->reqInfo->files();

//        $old = $this->_get($id);

        $update = ArrayHelper::filterKey(["content_name", "category_id", "content_description"], $params);
        if(count($update) > 0){
//            $update["updated_at"] = date("Y-m-d H:i:s");
            $update["updated_at"] = time();
        }

        $db = MedooFactory::getInstance();
        $db->pdo->beginTransaction();

        $db->update($this->table, $update, ["content_id"=> $id]);

        $updateBook = ArrayHelper::filterKey(['book_author', 'book_date', 'book_publishing_house', 'book_type_id', 'book_places'], $params);

        if(isset($updateBook['book_places'])){
            $updateBook['book_places'] = json_encode($updateBook['book_places']);
        }

        if(isset($files['book'])){
            $fileUpload = FileUpload::load($files['book']);
            if($fileUpload->isUploaded()){
                $newName = $fileUpload->generateName(true);
                $fileUpload->move("public/book/".$newName);
                $update['book_path'] = $newName;
            }
        }

        if(isset($files['book_cover'])){
            $fileUpload = FileUpload::load($files['book_cover']);
            if($fileUpload->isUploaded()){
                $newName = $fileUpload->generateName(true);
                $fileUpload->move("public/book_cover/".$newName);
                $update['book_cover_path'] = $newName;
            }
        }

        $db->update($this->table_book, $updateBook, ["content_id"=> $id]);
        $db->pdo->commit();

        return $this->_get($id);
    }

    /**
     * @POST
     * @uri /[i:id]/video
     */
    public function uploadVideo(){
        function insertVideo($video, $thumb, $video_name,$id, $db, $table_video){
            $seq = $db->max($table_video, "seq", ["content_id"=> $id]);
            $seq += 1;

            $insertVideo = ["content_id"=> $id, "seq"=> $seq, "video_name"=> $video_name];
            $video = FileUpload::load($video);
            $name = $video->generateName(true);
            $des = "public/video/".$name;
            $video->move($des);
            $insertVideo['video_path'] = $name;

            $video_thumb = FileUpload::load($thumb);
            $name = $video_thumb->generateName(true);
            $des = "public/video_thumb/".$name;
            $video_thumb->move($des);
            $insertVideo['video_thumb_path'] = $name;

            $db->insert($table_video, $insertVideo);
        }

        $params = $this->getReqInfo()->params();

        $cVideo = count($_FILES["videos"]["name"]);
        if($cVideo != count($_FILES["videos_thumb"]["name"]) || $cVideo != count($params['videos_name'])){
            return ResponseHelper::error("Video and name,thumb length not equals");
        }

        $id = $this->reqInfo->urlParam("id");
        $db = MedooFactory::getInstance();
        $db->pdo->beginTransaction();
        foreach($_FILES["videos"]["name"] as $key=> $value){
//                $videos[] = ["name"=> $_FILES["videos"]["name"][$key], "tmp_name"=> $_FILES["videos"]["tmp_name"][$key]];
            insertVideo(["name"=> $_FILES["videos"]["name"][$key], "tmp_name"=> $_FILES["videos"]["tmp_name"][$key]],
                ["name"=> $_FILES["videos_thumb"]["name"][$key], "tmp_name"=> $_FILES["videos_thumb"]["tmp_name"][$key]],
                $params['videos_name'][$key],
                $id, $db, $this->table_video);
        }
        $db->pdo->commit();

        return ["success"=> true];
    }

    /**
     * @DELETE
     * @uri /[i:id]/video
     */
    public function deleteVideo(){
        $list_id = $this->getReqInfo()->input("list_id");
        $content_id = $this->getReqInfo()->urlParam("id");
        $db = MedooFactory::getInstance();
        foreach($list_id as $key=> $id){
            $condition = ["content_id"=> $content_id];
            $c = $db->count($this->table_video, "*", $condition);
            if($c <= 1){
                break;
            }
            $condition = ["AND"=> ["content_id"=> $content_id, "id"=> $id]];
            $db->delete($this->table_video, $condition);
        }

        return ["success"=> true];
    }

    /**
     * @POST
     * @uri /[i:id]/video/sort
     */
    public function sortVideo(){
        $list_id = $this->getReqInfo()->input("list_id");
        $content_id = $this->getReqInfo()->urlParam("id");
        $db = MedooFactory::getInstance();
        foreach($list_id as $key=> $id){
            $condition = ["AND"=> ["content_id"=> $content_id, "id"=> $id]];
            $db->update($this->table_video, ["seq"=> $key + 1], $condition);
        }

        return ["success"=> true];
    }

    public function uploadAttachFile(){

    }

    public function deleteAttachFile(){

    }

    // internal function
    public function _get($id){
        $db = MedooFactory::getInstance();
        $item = $db->get($this->table, $this->join, ["*", "content.content_id"], ["content.content_id"=> $id]);
        if(!is_null($item)){
            $this->build($item);
        }

        return $item;
    }

    public function build(&$item){
        $db = MedooFactory::getInstance();
        if($item["content_type"]=="video"){
            $item["videos"] = $db->select($this->table_video, "*", ["content_id"=> $item["content_id"], "ORDER"=> "seq ASC"]);
            foreach($item["videos"] as $key=> $value){
                $item["videos"][$key]["video_url"] = URL::absolute("/public/video/".$item["videos"][$key]["video_path"]);
                $item["videos"][$key]["video_thumb_url"] = URL::absolute("/public/video_thumb/".$item["videos"][$key]["video_thumb_path"]);
            }
        }
        else {
            $item["book_url"] = URL::absolute("/public/book/".$item["book_path"]);
            $item["book_cover_url"] = URL::absolute("/public/book_cover/".$item["book_cover_path"]);
            $item["book_places"] = json_decode($item["book_places"]);
        }
        $item["attach_files"] = $db->select($this->table_attach_file, "*", ["content_id"=> $item["content_id"]]);
        foreach($item["attach_files"] as $key=> $value){
            $item["attach_files"][$key]["file_url"] = URL::absolute("/public/video/".$item["videos"][$key]["video_path"]);
        }
    }

    public function builds(&$items){
        foreach($items as $key => $item){
            $this->build($items[$key]);
        }
    }
}