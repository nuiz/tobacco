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
use Main\Http\FileUpload;

class BlogService extends BaseService {
    /**
     * @var Medoo $db;
     */
    private $db, $authUser;
    private $table = "blog_post", $table_like = "post_like";
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

        return $list;
    }

    public function add($params, $authUser){
        $insert = ArrayHelper::filterKey(['post_text', 'post_type'], $params);
        $insert['account_id'] = $authUser['account_id'];
        $insert['created_at'] = date("Y-m-d H:i:s");

        $this->db->pdo->beginTransaction();
        $id = $this->db->insert($this->table, $insert);

        if($insert['post_type']){
            $video = FileUpload::load($params["post_video"]["video"]);
            $this->_addVideo($params, $video);
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
    }

    public function getLike($post_id, $uid){
        return $this->db->get($this->table_like, "*", ["AND"=> ["post_id"=> $post_id, "account_id"=> $uid]]);
    }

    public function _get($id){
        $item = $this->db->get($this->table, "*", ["post_id"=> $id]);
        $this->_build($item);
        return $item;
    }

    public function _addVideo($params){

    }
}