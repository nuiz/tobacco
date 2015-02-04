<?php
/**
 * Created by PhpStorm.
 * User: NUIZ
 * Date: 29/1/2558
 * Time: 11:48
 */

namespace Main\Service;


use Main\Context\Context;
use Main\DB\Medoo\MedooFactory;
use Main\Helper\LangHelper;
use Main\Helper\ServiceHelper;

class CategoryService extends BaseService {

    private static $instance = null;
    private $table = "mastercategory";

    public static function getInstance(){
        if(is_null(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function gets($options = [], Context $ctx){
        $default = ServiceHelper::getDefaultOptionList();
        $options = array_merge($default, $options);
        $skip = ($options['page']-1)*$options['limit'];

        $db = MedooFactory::getInstance();
        $data = $db->select($this->table,
            '*', [
                'LIMIT'=> [$skip, $options['limit']],
                'ORDER'=> 'categoryId DESC'
            ]);

        $total = $db->count($this->table);
        foreach($data as $key => $value){
            $this->unSerializeData($data[$key], $ctx);
        }
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

    public function unSerializeData(&$data, Context $ctx){
        $data['category'] = LangHelper::getLang($data['category'], $ctx->getLang());
    }
}