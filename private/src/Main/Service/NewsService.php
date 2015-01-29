<?php
/**
 * Created by PhpStorm.
 * User: NUIZ
 * Date: 9/1/2558
 * Time: 9:58
 */

namespace Main\Service;


use Main\Context\Context;
use Main\DB\Medoo\MedooFactory;
use Main\Exception\Service\ServiceException;
use Main\Helper\ArrayHelper;
use Main\Helper\ResponseHelper;
use SebastianBergmann\Exporter\Exception;
use Valitron\Validator;

class NewsService extends BaseService {

    private static $instance = null;
    private $table = "news";

    public static function getInstance(){
        if(is_null(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }

    // insert news
    public function add($params, Context $ctx){
        $v = new Validator($params);
        $v->rule('required', ['title', 'content']);

        if(!$v->validate()){
            throw new ServiceException(ResponseHelper::validateError($v->errors()));
        }

        $params = ArrayHelper::filterKey(['title', 'content'], $params);

        $masterDB = MedooFactory::getInstance();

        $now = time();
        $insertParams = array_merge([
            'created_at'=> $now,
            'updated_at'=> $now
        ], $params);


        $id = $masterDB->insert($this->table, $insertParams);

        // get news data
        $item = $masterDB->select($this->table, '*', ['id'=> $id, "LIMIT"=> 1]);

        return $item[0];
    }

    public function gets($options = array(), Context $ctx){
        $default = array(
            "page"=> 1,
            "limit"=> 100
        );
        $options = array_merge($default, $options);
        $skip = ($options['page']-1)*$options['limit'];

        $masterDB = MedooFactory::getInstance();
        $data = $masterDB->select($this->table, '*', [
            'LIMIT'=> [$skip, $options['limit']],
            'ORDER'=> 'created_at DESC'
        ]);

        if(!$data){
            var_dump($masterDB->error());
            exit();
        }

        $total = $masterDB->count($this->table);

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

    public function edit($id, $params, Context $context){
        $updateParams = ArrayHelper::filterKey(['title', 'content'], $params);
        $updateParams['updated_at'] = time();

        $masterDB = MedooFactory::getInstance();
        $result = $masterDB->update($this->table, $updateParams, ['id'=> $id]);

        return $this->get($id, $context);
    }

    public function get($id, Context $context){
        $masterDB = MedooFactory::getInstance();
        $result = $masterDB->select($this->table, '*', ['id'=> $id, 'LIMIT'=> 1]);
        return @$result[0]? $result[0]: null;
    }

    public function delete($id, Context $context){
        $masterDB = MedooFactory::getInstance();

        $result = $masterDB->delete($this->table, ['id'=> $id]);

        return (bool)$result;
    }
}