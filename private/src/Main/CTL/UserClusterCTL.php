<?php
/**
 * Created by PhpStorm.
 * User: NUIZ
 * Date: 10/3/2558
 * Time: 4:11
 */

namespace Main\CTL;
use Main\DB\Medoo\MedooFactory;
use Main\Helper\ArrayHelper;
use Main\Helper\ResponseHelper;

/**
 * @Restful
 * @uri /usercluster
 */
class UserClusterCTL extends BaseCTL {
    private $table = "account";
    /**
     * @GET
     * @uri /[i:id]
     */
    public function get(){
        $id = $this->getReqInfo()->urlParam('id');
        return $this->_get($id);
    }

    /**
     * @PUT
     * @uri /[i:id]
     */
    public function edit(){
        $id = $this->getReqInfo()->urlParam('id');
        $params = $this->getReqInfo()->params();
        $update = ArrayHelper::filterKey(["username", "password", "firstname"], $params);

        $db = MedooFactory::getInstance();
        $old = $db->get($this->table, "*", ['account_id'=> $id]);

        if(isset($update['username']) && $update['username'] != $old['username']){
            $c = $db->count($this->table, "*", ["username"=> $update['username']]);
            if($c > 0){
                return ResponseHelper::error("duplicate username");
            }
        }

        $db->update($this->table, $update, ["AND"=>["account_id"=> $id, 'level_id'=> 3]]);
        return $this->_get($id);
    }

    /**
     * @DELETE
     * @uri /[i:id]
     */
    public function delete(){
        $id = $this->getReqInfo()->urlParam('id');
        $db = MedooFactory::getInstance();
        $db->delete($this->table, ["AND"=>["account_id"=> $id, 'level_id'=> 3]]);
        $db->update($this->table, ["level_id"=> 0, "cluster_id"=> null], ["AND"=>["level_id"=> 4, "cluster_id"=> $id]]);
        return ['success'=> true];
    }

    //internal function

    public function _get($id){
        $db = MedooFactory::getInstance();
        $account = $db->get($this->table, "*", ["AND"=>["account_id"=> $id, 'level_id'=> 3]]);
        return $account;
    }
}