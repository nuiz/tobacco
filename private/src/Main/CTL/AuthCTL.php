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
        $user = $db->get($this->table, "*", ["username"=> $params]);

        if(!$user){
            return [
                "error"=> [
                    "code"=> 1,
                    "message"=> "not found username"
                ]
            ];
        }

        if($user['password'] != $params['password']){
            return [
                "error"=> [
                    "code"=> 2,
                    "message"=> "wrong password"
                ]
            ];
        }

        if($user['auth_token'] == "" || is_null($user['auth_token'])){
            $token = $this->_generateToken($user['account_id']);
            $db->update($this->table, ["auth_token"=> $token]);
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

    public function _generateToken($account_id){
        return md5(uniqid($account_id, true));
    }
}