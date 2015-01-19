<?php
/**
 * Created by PhpStorm.
 * User: NUIZ
 * Date: 19/1/2558
 * Time: 13:31
 */

namespace Main\Service;


use Main\DB\Medoo\MedooFactory;
use Main\Helper\ArrayHelper;
use Main\Service\AccountService\AccountServiceException;
use Valitron\Validator;

class AccountService extends BaseService {
    private static $instance = null;

    public static function getInstance(){
        if(is_null(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }

    protected $table = "account", $detailTable = "accountDetail", $permissionTable = "permissionAccount";
    public function add($params){
        $v = new Validator($params);
        $v->rule('required', ['username', 'password', 'level']);
        $v->rule('in', 'level', ['user', 'admin']);

        if(!$v->validate()){
            error_log(print_r($v->errors(), true));
            throw new AccountServiceException();
        }

        $accInsert = ArrayHelper::filterKey(['username', 'password', 'level'], $params);
        $accInsert['status'] = 'pass';

        $db = MedooFactory::getInstance();

        $accId = $db->insert($this->table, $accInsert);
        if($accId==0){
            throw new AccountServiceException();
        }

        $accDetailField = ['name', 'lastname', 'email', 'phone', 'mobile', 'picture', 'introduced', 'history'];
        $accDetailInsert = ArrayHelper::filterKey(['name', 'lastname', 'email', 'phone', 'mobile', 'picture', 'introduced', 'history'], $params);
        foreach($accDetailField as $item){
            if(!isset($accDetailInsert[$item])){
                $accDetailInsert[$item] = "";
            }
        }
        $accDetailInsert['accId'] = $accId;

        if($accId == 0){
            throw new AccountServiceException();
        }
        $accDetailId = $db->insert($this->detailTable, $accDetailInsert);
//        if($accDetailId == 0){
//            throw new AccountServiceException();
//        }

        $permissionAccountInsert = [
            'accId'=> $accId,
            'name'=> $params['level'],
            'manageAdmin'=> 'yes',
            'manageUser'=> 'yes',
            'view'=> 'yes',
            'add'=> 'yes',
            'edit'=> 'yes',
            'del'=> 'yes',
            'download'=> 'yes',
            'approve'=> 'yes',
            'vote'=> 'yes',
            'share'=> 'yes',
            'comment'=> 'yes'
        ];

        $permissionAccId = $db->insert($this->permissionTable, $permissionAccountInsert);
//        if($accDetailId == 0){
//            throw new AccountServiceException();
//        }

        return $accId;
    }

    public function get($id){
        $db = MedooFactory::getInstance();
        $items = $db->select($this->table, '*', ['accId'=> $id, 'LIMIT'=> 1]);
        return $items;
        if(isset($items[0])){
            $item = $items[0];
            $item['registerDate'] = strtotime($item['registerDate']);
            return $item;
        }
        else {
            return null;
        }
    }

    public function update(){

    }

    public function delete($id){

    }

    public function gets(){

    }
}