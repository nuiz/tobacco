<?php
/**
 * Created by PhpStorm.
 * User: NUIZ
 * Date: 21/5/2558
 * Time: 13:45
 */

namespace Main\Service;


use Main\DAO\ListDAO;
use Main\DB\Medoo\Medoo;
use Main\DB\Medoo\MedooFactory;
use Main\Helper\ArrayHelper;
use Main\Helper\ResponseHelper;
use Main\Helper\URL;
use Main\Http\FileUpload;

class BlogService extends BaseService {
    /**
     * @var Medoo $db;
     */
    private $db, $authUser;
    private $table = "blog_post", $table_like = "post_like", $table_video = "post_video", $table_image = "post_image", $table_comment = "post_comment";
    private $pathVideo = "public/post_video/", $pathThumb = "/public/post_video_thumb/";
    public function setDb($db){
        $this->db = $db;
    }

    public function setAuthUser($authUser){
        $this->authUser = $authUser;
    }

    public function get($id){
        $item = MedooFactory::getInstance()->get($this->table, "*", ["post_id"=> $id]);
        $this->_build($item);

        return $item;
    }

    public function gets(){
        $options = $_GET;
        if(!isset($options["limit"])){
            $options["limit"] = 15;
        }

        $options["where"]["ORDER"] = "created_at DESC";
        $list = ListDAO::gets($this->table, $options);
        $this->_builds($list["data"]);

        return $list;
    }

    public function gets2($uid){
        $options = $_GET;
        if(!isset($options["limit"])){
            $options["limit"] = 15;
        }

        $options["where"] = ["account_id"=> $uid];

        $list = ListDAO::gets($this->table, $options);
        $this->_builds($list["data"]);

        return $list;
    }

    public function add($params, $authUser){
        $insert = ArrayHelper::filterKey(['post_text', 'post_type'], $params);
        $insert['account_id'] = $authUser['account_id'];
        $insert['created_at'] = date("Y-m-d H:i:s");

        $this->db->pdo->beginTransaction();
        $id = $this->db->insert($this->table, $insert);

        if($insert['post_type']=="video"){
//            $video = FileUpload::load($params["post_video"]["video"]);
            $video = $params["post_video"];
            if($video->getExt()!="mp4"){
                $this->db->pdo->rollBack();
                return ResponseHelper::error("Video upload not mp4");
            }
            $this->_addVideo($id, $video);
        }
        else if($insert['post_type']=="image"){
            $images = $params["post_image"];
            foreach($images as $img){
                if(!in_array($img->getExt(), ["jpeg", "jpg", "png"])){
                    $this->db->pdo->rollBack();
                    return ResponseHelper::error("Image upload not jpeg,jpg,png");
                }
            }
            $this->_addImage($id, $images);
        }

        $this->db->pdo->commit();

        return $this->_get($id);
    }

    public function like($id, $authUser){
        $uid = $authUser["account_id"];
        $c = $this->db->count($this->table_like, "*", ["AND"=> ["post_id"=> $id, "account_id"=> $uid]]);
        if($c == 0){
            $this->db->insert($this->table_like, ["post_id"=> $id, "account_id"=> $uid]);
            $this->db->update($this->table, ['like_count[+]'=> 1], ["post_id"=> $id]);
        }
    }

    public function unlike($id, $authUser){
        $uid = $authUser["account_id"];

        $c = $this->db->count($this->table_like, "*", ["AND"=> ["post_id"=> $id, "account_id"=> $uid]]);
        if($c != 0){
            $this->db->delete($this->table_like, ["AND"=> ["post_id"=> $id, "account_id"=> $uid]]);
            $this->db->update($this->table, ['like_count[-]'=> 1], ["post_id"=> $id]);
        }
    }

    public function _build(&$item){
        $aid = $this->authUser["account_id"];
        if(!$aid)
            $item['liked'] = false;
        $item['liked'] = (bool)$this->getLike($item['post_id'], $aid );

        if($item["post_type"]=="video"){
            $itemVideo = $this->db->get($this->table_video, "*", ["post_id"=> $item["post_id"]]);
            $item["video_url"] = URL::absolute("/")."/public/post_video/".$itemVideo["video_path"];
        }

        if($item["post_type"]=="image"){
            $item["images"] = [];
            $itemImages = $this->db->select($this->table_image, "*", ["post_id"=> $item["post_id"]]);
            foreach($itemImages as $img){
                $img["image_url"] = URL::absolute("/")."/public/post_image/".$img["image_path"];
                $item["images"][] = $img;
            }
        }

        $item["user"] = $this->db->get("account", ["account_id", "firstname", "lastname", "username"], ["account_id"=> $item["account_id"]]);
        $picPath = "public/image_users/".$item["user"]["username"].".png";
        if(file_exists($picPath)){
            $item["user"]["picture"] = URL::absolute("/").$picPath;
        }
        else {
            $item["user"]["picture"] = URL::absolute("/")."public/images/user.jpg";
        }
    }

    public function _builds(&$items){
        foreach($items as $key => $item){
            $this->_build($items[$key]);
        }
    }

    public function getLike($post_id, $uid){
        return $this->db->get($this->table_like, "*", ["AND"=> ["post_id"=> $post_id, "account_id"=> $uid]]);
    }

    public function _get($id){
        $item = $this->db->get($this->table, "*", ["post_id"=> $id]);
        $this->_build($item);
        return $item;
    }

    public function _addVideo($id, FileUpload $video){
        $des = "public/post_video/";
        $videoName = $video->generateName(true);
        $des .= $videoName;
        $video->move($des);

        $this->db->insert($this->table_video, ["post_id"=> $id, "video_path"=> $videoName]);
    }

    /**
     * @param $id
     * @param FileUpload[] $images
     */
    public function _addImage($id, $images){
        foreach($images as $img){
            $des = "public/post_image/";
            $imageName = $img->generateName(true);
            $des .= $imageName;
            $img->move($des);

            $this->db->insert($this->table_image, ["post_id"=> $id, "image_path"=> $imageName]);
        }
    }

    public function addComment($post_id, $params, $authUser){
        $id = $this->db->insert($this->table_comment, [
            "post_id"=> $post_id,
            "comment_text"=> $params["comment_text"],
            "account_id"=> $authUser["account_id"],
            "created_at"=> date("Y-m-d H:i:s")
        ]);

        $this->db->update($this->table, ['comment_count[+]'=> 1], ["post_id"=> $post_id]);

        return $this->getComment($id);
    }

    public function getComment($id){
        $item = $this->db->get($this->table_comment, "*", ["comment_id"=> $id]);
        $this->_buildComment($item);

        return $item;
    }

    public function getComments($post_id){
        $options = $_GET;
        if(!isset($options["limit"])){
            $options["limit"] = 100;
        }

        $options["where"]["ORDER"] = "created_at ASC";
        $options["where"]["post_id"] = $post_id;
        $list = ListDAO::gets($this->table_comment, $options);
        $this->_buildComments($list["data"]);

        return $list;
    }

    public function _buildComment(&$item){
        $item["user"] = $this->db->get("account", ["account_id", "firstname", "lastname", "username"], ["account_id"=> $item["account_id"]]);
        $picPath = "public/image_users/".$item["user"]["username"].".png";
        if(file_exists($picPath)){
            $item["user"]["picture"] = URL::absolute("/").$picPath;
        }
        else {
            $item["user"]["picture"] = URL::absolute("/")."public/images/user.jpg";
        }
    }

    public function _buildComments(&$items){
        foreach($items as $key => $item){
            $this->_buildComment($items[$key]);
        }
    }
}