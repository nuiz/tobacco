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
use Main\Helper\URL;

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

        if(empty($username)){
            return ResponseHelper::error("Require username");
        }
        if(empty($password)){
            return ResponseHelper::error("Require password");
        }

        $ldap_login = false;
        if (preg_match('/^[0-9]+$/', $username)) {
            $ldap_login = file_get_contents("http://192.168.0.238/ldap_login.php?".http_build_query([
                "username"=> $username,
                "password"=> $password
                ])) == "1" ? true: false;
        }

        $db = MedooFactory::getInstance();
        $account = $db->get("account", "*", ["username"=> $username]);

        if(is_null($account) || !$account){
            return ResponseHelper::error("Username not found");
        }
        if(!$ldap_login && $account['password'] != $password && $account['password'] != md5($password)){
            return ResponseHelper::error("Wrong password");
        }

        if($account["auth_token"] == ""){
            $account["auth_token"] = GenerateHelper::tokenById($account["account_id"]);
        }

        $picPath = "public/image_users/".$account["username"].".png";
        if(file_exists($picPath)){
            $account["picture"] = URL::absolute("/").$picPath;
        }
        else {
            $account["picture"] = URL::absolute("/")."public/images/user.jpg";
        }

        $db->update($this->table, ["auth_token"=> $account["auth_token"]], ["account_id"=> $account["account_id"]]);


        // ***************** add point **************

        $clientType = $this->reqInfo->input("client_type", "mobile");
        $addPoint = ($clientType == 'mobile')? 10 : 5;

        $where = ["account_id"=> $account["account_id"]];

        $accountPoint = $db->get("account_point", "*", ["account_id"=> $account["account_id"]]);

        $point = $addPoint;
        if(!$accountPoint) {
            $insert = $where;
            $insert['point'] = $addPoint;
            if($clientType == 'pc') {
                $insert['last_login_pc'] = date('Y-m-d H:i:s');
            }
            else if($clientType == 'kiosk') {
                $insert['last_login_kiosk'] = date('Y-m-d H:i:s');
            }
            else {
                $insert['last_login_mobile'] = date('Y-m-d H:i:s');
            }
            $db->insert("account_point", $insert);
        }
        else {
            $point = $accountPoint['point'];
            $update = ['point[+]' => $addPoint];
            $lastLogin = time();
            if($clientType == 'pc') {
                $update['last_login_pc'] = date('Y-m-d H:i:s');
                $lastLogin = strtotime($accountPoint['last_login_pc']);
            }
            else if($clientType == 'kiosk') {
                $update['last_login_kiosk'] = date('Y-m-d H:i:s');
                $lastLogin = strtotime($accountPoint['last_login_kiosk']);
            }
            else {
                $update['last_login_mobile'] = date('Y-m-d H:i:s');
                $lastLogin = strtotime($accountPoint['last_login_mobile']);
            }
            if($lastLogin + (24*60*60) <= time()) {
                $point += $addPoint;
                $db->update("account_point", $update, $where);
            }
        }

        $account['point'] = $point;
        return $account;
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
            @ldap_close($ldap);
            return true;
        } else {
            return false;
        }
    }
}