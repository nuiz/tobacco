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

    protected $table = "account";

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
        return $this->get($id);
    }

    public function delete($id){
        $db = MedooFactory::getInstance();
        return $db->delete($this->table, ["accId"=> $id]);
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
            '*', [
                'LIMIT'=> [$skip, $options['limit']],
                'ORDER'=> 'account_id DESC'
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