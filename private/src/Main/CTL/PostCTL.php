<?php
/**
 * Created by PhpStorm.
 * User: NUIZ
 * Date: 20/5/2558
 * Time: 16:38
 */

namespace Main\CTL;


use Main\DB\Medoo\Medoo;
use Main\DB\Medoo\MedooFactory;

class PostCTL extends BaseCTL {
    /**
     * @var Medoo $db;
     */
    private $db;

    public function _build(&$item){
        $uid = $this->getUid();
        if(!$uid)
            $item['liked'] = false;
        $item['liked'] = (bool)$this->getLike($item['post_id'], $uid);
    }

    public function getLike($post_id, $uid){
        $db = $this->db;
        return $db->get("post_like", "*", ["post_id"=> $post_id, "account_id"=> $uid]);
    }

    public function getDB(){
        if(is_null($this->db)){
            $this->db = MedooFactory::getInstance();
        }
        return $this->db;
    }

    public function setDB($db){
        $this->db = $db;
    }

    public function getUid(){
        $u = $this->getReqInfo()->getAuthAccount();
        if(!is_null($u) && isset($u->account_id))
            return $u->account_id;
        return false;
    }
}