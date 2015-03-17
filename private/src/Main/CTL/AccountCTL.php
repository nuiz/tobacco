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
        $params = ArrayHelper::filterKey(["username", "password"], $params);
        $params["level_id"] = 3;

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
        $params = $this->reqInfo->params();
        $params["url"] = URL::absolute("/account/writer");
        $params["where"] = [
            "level_id"=> 4,
            "ORDER"=> "account_id DESC"
        ];
        $listResponse = ListDAO::gets($this->table, $params);
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

        $id = $db->update($this->table, ["level_id"=> 4], ["account_id"=> $params["account_id"]]);

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