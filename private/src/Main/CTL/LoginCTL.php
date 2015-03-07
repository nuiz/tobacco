<?php
/**
 * Created by PhpStorm.
 * User: NUIZ
 * Date: 3/3/2558
 * Time: 10:11
 */

namespace Main\CTL;
use Main\DB\Medoo\MedooFactory;
use Main\Helper\ResponseHelper;
use Main\Helper\GenerateHelper;

/**
 * @Restful
 * @uri /login
 */
class LoginCTL extends BaseCTL {
    private $table = "account";
    /**
     * @POST
     */
    public function post(){
        $username = $this->reqInfo->param("username");
        $password = $this->reqInfo->param("password");

        $db = MedooFactory::getInstance();
        $account = $db->get("account", "*", ["username"=> $username]);

        if(is_null($account) || !$account){
            return ResponseHelper::error("Username not found");
        }
        if($account["password"] != $password){
            return ResponseHelper::error("Wrong password");
        }

        if($account["auth_token"] == ""){
            $account["auth_token"] = GenerateHelper::tokenById($account["account_id"]);
        }

        $db->update($this->table, ["auth_token"=> $account["auth_token"]], ["account_id"=> $account["account_id"]]);

        return $account;
    }
}