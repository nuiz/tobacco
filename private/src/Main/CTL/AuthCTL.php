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
use Main\Helper\URL;

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

        $ldap_username = false;
        if (preg_match('/^[0-9]+$/', $params["username"])) {
            $ldap_username = $this->_ad($params["username"], $params["password"]);
        }

        $db = MedooFactory::getInstance();
        $user = $db->get($this->table, "*", ["AND"=> ["username"=> $params["username"], "account_status[!]"=> 3]]);

        if(!$user){
            return [
                "error"=> [
                    "code"=> 1,
                    "message"=> "not found username"
                ]
            ];
        }

        if(!$ldap_username && $user['password'] != $params['password'] && md5($user['password']) != $params["password"]){
            return [
                "error"=> [
                    "code"=> 2,
                    "message"=> "wrong password",
                    "ldap"=> $ldap_username
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
        $authAccount = $this->reqInfo->getAuthAccount();
        if(!empty($authAccount)){
            return $this->registerNfc();
        }

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

        $picPath = "public/image_users/".$acc["username"].".png";
        if(file_exists($picPath)){
            $acc["picture"] = URL::absolute("/").$picPath;
        }
        else {
            $acc["picture"] = URL::absolute("/")."public/images/user.jpg";
        }
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

    public function registerNfc(){
        $params  = $this->getReqInfo()->params();
        $nfc_id = $params["nfc_id"];
        $authAccount = $this->reqInfo->getAuthAccount();

        $db = MedooFactory::getInstance();
        $account_nfc = $db->get("account_nfc", "*", ["account_id"=> $authAccount['account_id']]);
        if(!$account_nfc){
            $db->insert("account_nfc", [
                "account_id"=> $authAccount['account_id'],
                "nfc_id"=> $params['nfc_id'],
                "register_client_id"=> $params['kiosk_id'],
                "register_at"=> date('Y-m-d H:i:s')]);

            $db->update("client", [
                'client_nfc_register[+]'=> 1
            ], [
                'client_id'=> $params['kiosk_id']
            ]);
            return ["register_nfc"=> true, "success"=> true];
        }
        else {
            return ["register_nfc"=> true, "success"=> false];
        }
    }

    public function _ad($username, $password){
        $ldap_url = "tobacco.or.th";
        $ldap_domain = "tobacco.or.th";
        $ldap_dn = "DC=tobacco,DC=or,DC=th";

        $ldap = ldap_connect($ldap_url);

        $ldaprdn = $username . "@" . $ldap_domain;

        ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);

        $bind = @ldap_bind($ldap, $ldaprdn, $password);

        if ($bind) {
            $filter="(sAMAccountName=$username)";
            $result = ldap_search($ldap, $ldap_dn, $filter);
            ldap_sort($ldap, $result, "sn");
            $info = ldap_get_entries($ldap, $result);
            for ($i=0; $i<$info["count"]; $i++)
            {
                if($info['count'] > 1)
                    break;

                $username = $info[$i]["samaccountname"][0];
                @ldap_close($ldap);

                return $username;
            }
            @ldap_close($ldap);
        } else {
            return false;
        }
    }


    public function _generateToken($account_id){
        return md5(uniqid($account_id, true));
    }
}
