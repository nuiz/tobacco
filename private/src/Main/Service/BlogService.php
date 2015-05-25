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
    private $table = "blog_post", $table_like = "post_like", $table_video = "post_video";
    private $pathVideo = "public/post_video/", $pathThumb = "/public/post_video_thumb/";
    public function setDb($db){
        $this->db = $db;
    }

    public function setAuthUser($authUser){
        $this->authUser = $authUser;
    }

    public function gets(){
        $options = $_GET;
        if(!isset($options["limit"])){
            $options["limit"] = 15;
        }

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

        if($insert['post_type']){
//            $video = FileUpload::load($params["post_video"]["video"]);
            $video = $params["post_video"];
            if($video->getExt()!="mp4"){
                $this->db->pdo->rollBack();
                return ResponseHelper::error("Video upload not mp4");
            }
            $this->_addVideo($id, $video);
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

        $this->db->insert($this->table_video, ["id"=> $id, "video_path"=> $videoName]);
    }
}