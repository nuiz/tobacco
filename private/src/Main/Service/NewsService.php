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
use Main\Helper\ArrayHelper;
use Valitron\Validator;

class NewsService extends BaseService {

    protected $table = "news";

    // insert news
    public function add($params, Context $ctx){
        $v = new Validator($params);
        $v->rule('required', ['title', 'content']);

        $params = ArrayHelper::filterKey(['title', 'content'], $params);

        $masterDB = MedooFactory::getInstance()->insert($this->table, $params);
    }
}