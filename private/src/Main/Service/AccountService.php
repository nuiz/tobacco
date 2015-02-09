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

        return $accId;
    }

    public function get($id){
        $db = MedooFactory::getInstance();
        $item = $db->get($this->table,
            '*',
            [$this->table.'.accId'=> $id]);

        return $item;
    }

    public function updateDetail($id, $params){

        $paramUpdate = ArrayHelper::filterKey(['name', 'lastname', 'email', 'phone', 'mobile', 'picture', 'introduced', 'history'], $params);
        if(count($paramUpdate) == 0){
            return $this->get($id);
        }

        $db = MedooFactory::getInstance();
        $count = $db->count($this->table, ["accId"=> $id]);
        if($count == 0){
            return $this->get($id);
        }

        $db->update($this->table, $paramUpdate, ["accId"=> $id]);
        return $this->get($id);
    }

    public function delete($id){
        $db = MedooFactory::getInstance();
        return $db->delete($this->table, ["accId"=> $id])
            && $db->delete($this->detailTable, ["accId"=> $id])
            && $db->delete($this->permissionTable, ["accId"=> $id]);
    }

    public function gets($options){
        $default = array(
            "page"=> 1,
            "limit"=> 100
        );
        $options = array_merge($default, $options);
        $skip = ($options['page']-1)*$options['limit'];

        $db = MedooFactory::getInstance();
        $data = $db->select($this->table,
            ["[>]".$this->detailTable => ['accId', 'accId']],
            '*', [
                'LIMIT'=> [$skip, $options['limit']],
                'ORDER'=> 'accId DESC'
            ]);

        $total = $db->count($this->table);

        $res = [
            'length'=> count($data),
            'total'=> $total,
            'data'=> $data,
            'paging'=> [
                'page'=> (int)$options['page'],
                'limit'=> (int)$options['limit']
            ]
        ];
        return $res;
    }
}