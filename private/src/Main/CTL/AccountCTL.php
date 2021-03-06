<?php
/**
 * Created by PhpStorm.
 * User: NUIZ
 * Date: 19/1/2558
 * Time: 13:31
 */

namespace Main\CTL;
use Main\DAO\ListDAO;
use Main\Helper\ArrayHelper;
use Main\Helper\ResponseHelper;
use Main\Helper\URL;
use Main\Permission\AccountPermission;
use Valitron\Validator;
use Main\DB\Medoo\MedooFactory;
use Main\Service\AccountService\AccountServiceException;
use Main\Service\AccountService;
use Main\View\JsonView;

/**
 * @Restful
 * @uri /account
 */
class AccountCTL extends BaseCTL {
    private $table = "account";
    /**
     * @POST
     */
    public function add(){
        MedooFactory::getInstance()->pdo->beginTransaction();
        $params = $this->reqInfo->params();
        try {
            $id = AccountService::getInstance()->add($params);
            MedooFactory::getInstance()->pdo->commit();
            $item = AccountService::getInstance()->get($id);
            return new JsonView($item);
        }
        catch (AccountServiceException $e){
            MedooFactory::getInstance()->pdo->rollBack();
            error_log($e);
            return new JsonView(ResponseHelper::error('Error'));
        }
    }

    /**
     * @POST
     * @uri /cluster
     */
    public function addCluster(){
        $params = $this->reqInfo->params();
        $params = ArrayHelper::filterKey(["username", "password", "firstname", "cluster_id"], $params);
        $params["level_id"] = 3;

        if($params["cluster_id"]){

        }

        $user = $this->reqInfo->getAuthAccount();
        AccountPermission::requirePermission($user, [AccountPermission::ID_CLUSTER_IT, AccountPermission::ID_SUPER_ADMIN]);

        $db = MedooFactory::getInstance();

        if($db->count($this->table, ["username"=> $params["username"]]) > 0){
            return [
                "error"=> [
                    "code"=> 1,
                    "message"=> "duplicate username"
                ]
            ];
        }

        $params['created_at'] = date('Y-m-d H:i:s');

        $id = $db->insert($this->table, $params);

        $item = $this->_get($id);
        return new JsonView($item);
    }

    /**
     * @POST
     * @uri /writer
     */
    public function addWriter(){
        $params = $this->reqInfo->params();
        $params = ArrayHelper::filterKey(["username", "password"], $params);
        $params["level_id"] = 4;

        $user = $this->reqInfo->getAuthAccount();
        AccountPermission::requirePermission($user, [AccountPermission::ID_CLUSTER, AccountPermission::ID_CLUSTER_IT, AccountPermission::ID_SUPER_ADMIN]);

        $db = MedooFactory::getInstance();
        $params['cluster_id'] = $user->account_id;

        if($db->count($this->table, ["username"=> $params["username"]]) > 0){
            return [
                "error"=> [
                    "code"=> 1,
                    "message"=> "duplicate username"
                ]
            ];
        }

        $id = $db->insert($this->table, $params);

        $item = $this->_get($id);
        return new JsonView($item);
    }

    /**
     * @GET
     * @uri /cluster
     */
    public function listCluster(){
        $params = $this->reqInfo->params();
        $params["url"] = URL::absolute("/account/cluster");
        $params["where"] = [
            "level_id"=> 3,
            "ORDER"=> "account_id DESC"
        ];
        $listResponse = ListDAO::gets($this->table, $params);
        return new JsonView($listResponse);
    }

    /**
     * @GET
     * @uri /writer
     */
    public function listWriter(){
        $db = MedooFactory::getInstance();

        $params = $this->reqInfo->params();
        $params["url"] = URL::absolute("/account/writer");
        $params["where"] = [
            "AND"=> [
                "level_id"=> 4,
            ],
            "ORDER"=> "account_id DESC"
        ];
        $cluster_id = $this->getReqInfo()->param("cluster_id");
        if(!empty($cluster_id)){
            $params["where"]["AND"]["cluster_id"] = $cluster_id;
        }
        $listResponse = ListDAO::gets($this->table, $params);
        foreach($listResponse['data'] as $key=> $value){
            $value['cluster'] = $db->get("account", "*", [
                "AND"=> [
                    "account_id"=> $value["cluster_id"],
                    "level_id"=> 3
                ]
            ]);
            $listResponse['data'][$key] = $value;
        }
        return new JsonView($listResponse);
    }

    /**
     * @GET
     * @uri /user
     */
    public function listUser(){
        $params = $this->reqInfo->params();
        $params["url"] = URL::absolute("/account/user");
        $params["field"] = ["account_id", "username", "firstname", "lastname"];
        $params["where"] = [
            "level_id"=> 0,
            "ORDER"=> "account_id DESC",
            "LIMIT"=> 1000
        ];
        $listResponse = ListDAO::gets($this->table, $params);
        return new JsonView($listResponse);
    }


    /**
     * @POST
     * @uri /upwriter
     */
    public function upwriter(){
        $params = $this->reqInfo->params();
        $db = MedooFactory::getInstance();

        $user = $this->getReqInfo()->getAuthAccount();
        AccountPermission::requirePermission($user, [AccountPermission::ID_CLUSTER, AccountPermission::ID_CLUSTER_IT, AccountPermission::ID_SUPER_ADMIN]);

        if($user["level_id"]==AccountPermission::ID_CLUSTER){
            $cluster_id = $user['account_id'];
        }
        else if($user["level_id"]==AccountPermission::ID_CLUSTER_IT || $user["level_id"]==AccountPermission::ID_CLUSTER || $user["level_id"]==AccountPermission::ID_SUPER_ADMIN) {
            $cluster_id = $params["cluster_id"];
        }
        $id = $db->update($this->table, ["level_id"=> 4, "cluster_id"=> $cluster_id, "upgrade_level_at"=> date("Y-m-d H:i:s")], ["account_id"=> $params["account_id"]]);

        $item = $this->_get($params["account_id"]);
        return new JsonView($item);
    }

    /**
     * @PUT
     * @uri /[:id]
     */
    public function update(){
        $id = $this->reqInfo->urlParam('id');
        $params = $this->reqInfo->params();

        MedooFactory::getInstance()->pdo->beginTransaction();
        try {
            AccountService::getInstance()->updateDetail($id, $params);
            MedooFactory::getInstance()->pdo->commit();
            $item = AccountService::getInstance()->get($id);
            return new JsonView($item);
        }
        catch (AccountServiceException $e){
            MedooFactory::getInstance()->pdo->rollBack();
            error_log($e);
            return new JsonView(ResponseHelper::error('Error'));
        }
    }

    /**
     * @DELETE
     * @uri /cluster/[:id]
     */
    public function deleteCluster(){
        $id = $this->reqInfo->urlParam('id');

        $user = $this->reqInfo->getAuthAccount();
        AccountPermission::requirePermission($user, [AccountPermission::ID_CLUSTER, AccountPermission::ID_CLUSTER_IT, AccountPermission::ID_SUPER_ADMIN]);

        $db = MedooFactory::getInstance();
        $ss = $db->delete($this->table, ["AND"=> ["account_id"=> $id, "level_id"=> 3]]);

        return ["success"=> true];
    }

    /**
     * @DELETE
     * @uri /writer/[:id]
     */
    public function deleteWriter(){
        $id = $this->reqInfo->urlParam('id');

        $user = $this->reqInfo->getAuthAccount();
        AccountPermission::requirePermission($user, [AccountPermission::ID_CLUSTER, AccountPermission::ID_CLUSTER_IT, AccountPermission::ID_SUPER_ADMIN]);

        $db = MedooFactory::getInstance();
        $db->delete($this->table, ["account_id"=> $id, "level"=> 4]);

        return ["success"=> true];
    }

    /**
     * @POST
     * @uri /change_password/[:id]
     */
    public function changePassword(){
        $id = $this->reqInfo->urlParam('id');
        $params = $this->reqInfo->params();

        $v = new Validator($params);
        $v->rule("required", ["new_password"]);

        $db = MedooFactory::getInstance();
        $db->update($this->table, ["password"=> $params["new_password"]], ["account_id"=> $id]);

        return ["success"=> true];
    }

    /**
     * @GET
     * @uri /[i:id]
     */
    public function get(){
        $id = $this->reqInfo->urlParam('id');

        $db = MedooFactory::getInstance();
        $item = $db->get($this->table,
            '*',
            ["account_id"=> $id]);

        if(!$item){
            $item = null;
        }
        
        $picPath = "public/image_users/".$item["username"].".png";
        if(file_exists($picPath)){
            $item["picture"] = URL::absolute("/").$picPath;
        }
        else {
            $item["picture"] = URL::absolute("/")."public/images/user.jpg";
        }

        return new JsonView($item);
    }

    /**
     * @GET
     */
    public function gets(){
        $params = $this->reqInfo->params();
        try {
            $item = AccountService::getInstance()->gets($params);
            return new JsonView($item);
        }
        catch (AccountServiceException $e){
            Log($e);
            return new JsonView(ResponseHelper::error('Error'));
        }
    }

    // internal function
    public function _get($id){
        $db = MedooFactory::getInstance();
        return $db->get($this->table, "*", ["account_id"=> $id]);
    }
}