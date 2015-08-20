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
use Main\Helper\URL;

/**
 * @Restful
 * @uri /client_date
 */
class ClientDateCTL extends BaseCTL {
    private $table = "client_date";

    /**
     * @GET
     */
    public function gets(){
        $params = $this->reqInfo->params();
        $db = MedooFactory::getInstance();

        $data = $db->select($this->table, ["[>]client"=> ["client_id"=> "client_id"]], "*");

        return [
            "length"=> count($data),
            "data"=> $data
        ];
    }
}