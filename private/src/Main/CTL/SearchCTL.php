<?php
/**
 * Created by PhpStorm.
 * User: NUIZ
 * Date: 11/1/2558
 * Time: 22:33
 */

namespace Main\CTL;
use Main\Helper\ArrayHelper;
use Main\Permission\AccountPermission;
use Main\View\JsonView;
use Valitron\Validator;
use Main\Helper\ResponseHelper;
use Main\DB\Medoo\MedooFactory;
use Main\DB\MongoFactory;
use Main\Helper\URL;

/**
 * @Restful
 * @uri /search
 */
class SearchCTL extends BaseCTL {
    private $table = "account";

    /**
     * @GET
     */
    public function gets(){
        $params = $this->reqInfo->params();
        $keyword = $params["keyword"];
        $mongo = MongoFactory::getConnection();
        $db = MedooFactory::getInstance();
        $pdo = $db->pdo;

        $regex = new \MongoRegex("/{$keyword}/");

        $coll = $mongo->tobacco_search->object_keyword;
        $cursor = $coll->find(['keyword'=> $regex]);

        $data = [];
        foreach($cursor as $key=> $item){
            $data[] = $item;
        }

        $mongo->close();

        return $data;
    }
}