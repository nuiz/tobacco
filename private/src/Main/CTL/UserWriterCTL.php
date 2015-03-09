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

/**
 * @Restful
 * @uri /userwriter
 */
class UserWriterCTL extends BaseCTL {
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
     * @DELETE
     * @uri /[i:id]
     */
    public function delete(){
        $id = $this->getReqInfo()->urlParam('id');
        $db = MedooFactory::getInstance();
        $db->update($this->table, ['level_id'=> 0], ["AND"=>["account_id"=> $id, 'level_id'=> 4]]);
        return ['success'=> true];
    }

    //internal function

    public function _get($id){
        $db = MedooFactory::getInstance();
        $account = $db->get($this->table, "*", ["AND"=>["account_id"=> $id, 'level_id'=> 4]]);
        return $account;
    }
}