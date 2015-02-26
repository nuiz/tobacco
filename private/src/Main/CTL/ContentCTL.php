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
    private $table = "content", $table_book = "content_book", $table_video = "content_video";
    private $join = [
        "[>]content_video"=> ["content_id"=> "content_id"],
        "[>]content_book"=> ["content_id"=> "content_id"],
//        "[>]content_pdf"=> ["content_id"=> "content_id"],
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

        if(isset($params["parent_id"])){
            $params["where"]["parent_id"] = $params["parent_id"];
        }

        $db = MedooFactory::getInstance();

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
        AccountPermission::requirePermission($user, [AccountPermission::ID_CLUSTER_IT, AccountPermission::ID_SUPER_ADMIN, AccountPermission::ID_WRITER]);

        // begin transaction
        $db = MedooFactory::getInstance();
        $db->pdo->beginTransaction();

        $insert["account_id"] = $user["account_id"];
        $id = $db->insert($this->table, $insert);

        if($insert["content_type"] == "book"){
            $insertBook = ArrayHelper::filterKey(["book_author", "book_publishing_house", "book_date", "book_type_id"], $params);
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

            $db->insert($this->table_book, $insertBook);
            $db->pdo->commit();
        }

        if($insert["content_type"] == "video"){
            $insertVideo = ["content_id"=> $id];

            $video = FileUpload::load($this->reqInfo->file("video"));
            $name = $video->generateName(true);
            $des = "public/video/".$name;
            $video->move($des);
            $insertVideo['video_path'] = $name;

            $video_thumb = FileUpload::load($this->reqInfo->file("video_thumb"));
            $name = $video_thumb->generateName(true);
            $des = "public/video_thumb/".$name;
            $video_thumb->move($des);
            $insertVideo['video_thumb_path'] = $name;

            $db->insert($this->table_video, $insertVideo);
            $db->pdo->commit();
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
            return $this->_editHtml();
        }
        else if($type == "video"){
            return $this->_editVideo();
        }
        else {
            return ResponseHelper::error("Only type book, video");
        }
    }

    public function _editHtml(){
        $id = $this->reqInfo->urlParam('id');
        $params = $this->reqInfo->params();

        $old = $this->_get($id);

        $update = ArrayHelper::filterKey(["content_name", "category_id", "content_text"], $params);

        $db = MedooFactory::getInstance();
        $db->pdo->beginTransaction();

        $db->update($this->table, $update, ["content_id"=> $id]);

        if($old["content_type"] == "book"){
            $updateHtml = ArrayHelper::filterKey(["content_book"], $params);
            $db->update($this->table_book, $updateHtml, ["content_id"=> $id]);
            $db->pdo->commit();
        }

        return $this->_get($id);
    }

    public function _editVideo(){
        $id = $this->reqInfo->urlParam('id');
        $params = $this->reqInfo->params();

        $old = $this->_get($id);

        $update = ArrayHelper::filterKey(["content_name", "category_id", "content_text"], $params);

        $db = MedooFactory::getInstance();
        $db->pdo->beginTransaction();

        $db->update($this->table, $update, ["content_id"=> $id]);

        if($old["content_type"] == "video"){
            $updateVideo = [];
            $video = FileUpload::load($this->reqInfo->file("video"));
            if($video->isUploaded()){
                $des = "public/video/".$video->generateName(true);
                $video->move($des);

                $updateVideo["video_path"] = $des;
            }

            $db->update($this->table_video, $updateVideo, ["content_id"=> $id]);
            $db->pdo->commit();
        }

        return $this->_get($id);
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
        $item["video_url"] = URL::absolute("/public/video/".$item["video_path"]);
        $item["video_thumb_url"] = URL::absolute("/public/video_thumb/".$item["video_thumb_path"]);
        $item["book_url"] = URL::absolute("/public/book/".$item["book_path"]);
        $item["book_cover_url"] = URL::absolute("/public/book_cover/".$item["book_cover_path"]);
    }

    public function builds(&$items){
        foreach($items as $key => $item){
            $this->build($items[$key]);
        }
    }
}