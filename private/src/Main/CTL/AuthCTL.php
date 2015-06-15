<?php
/**
 * Created by PhpStorm.
 * User: NUIZ
 * Date: 2/2/2558
 * Time: 15:28
 */

namespace Main\CTL;
use Main\DB\Medoo\MedooFactory;
use Main\Helper\ArrayHelper;
use Main\Helper\ResponseHelper;

/**
 * @Restful
 * @uri /auth
 */
class AuthCTL extends BaseCTL {
    private $table = "account";
    /**
     * @POST
     * @uri /password
     */
    public function password(){
        $params = ArrayHelper::filterKey(["username", "password"], $this->reqInfo->params());
        $db = MedooFactory::getInstance();
        $user = $db->get($this->table, "*", ["AND"=> ["username"=> $params, "account_status[!]"=> 3]]);

        if(!$user){
            return [
                "error"=> [
                    "code"=> 1,
                    "message"=> "not found username"
                ]
            ];
        }

        if($user['password'] != $params['password'] && md5($user['password']) != $params["password"]){
            return [
                "error"=> [
                    "code"=> 2,
                    "message"=> "wrong password"
                ]
            ];
        }

        if($user['auth_token'] == "" || is_null($user['auth_token'])){
            $token = $this->_generateToken($user['account_id']);
            $db->update($this->table, ["auth_token"=> $token], ["account_id"=> $user["account_id"]]);
            return [
                "auth_token"=> $token
                ];
        }
        else {
            return [
                "auth_token"=> $user['auth_token']
            ];
        }
    }

    /**
     * @GET
     * @POST
     * @uri /nfc
     */
    public function nfc(){
        $params  = $this->getReqInfo()->params();
        $nfc_id = $params["nfc_id"];

        $db = MedooFactory::getInstance();
        $acc = $db->get("account_nfc", "*", ["nfc_id"=> $nfc_id]);
        if(!$acc){
            return ResponseHelper::error("Auth failed nfc wrong");
        }
        $acc = $db->get("account", "*", ["account_id"=> $acc["account_id"]]);
        if(!$acc){
            return ResponseHelper::error("Auth failed");
        }
        unset($acc["password"]);
//        unset($acc["cluster_id"]);
//        unset($acc["upgrade_level_at"]);
//        unset($acc["cluster_id"]);
//        unset($acc["created_at"]);
//        unset($acc["updated_at"]);

        if($acc['auth_token'] == "" || is_null($acc['auth_token'])){
            $token = $this->_generateToken($acc['account_id']);
            $db->update($this->table, ["auth_token"=> $token], ["account_id"=> $acc["account_id"]]);
//            return [
//                "auth_token"=> $token
//            ];
            $acc["auth_token"] = $token;
            return $acc;
        }
        else {
            return $acc;
//            return [
//                "auth_token"=> $acc['auth_token']
//            ];
        }

        return $acc;
    }

    public function _generateToken($account_id){
        return md5(uniqid($account_id, true));
    }
}