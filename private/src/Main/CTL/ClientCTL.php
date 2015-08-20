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
 * @uri /client
 */
class ClientCTL extends BaseCTL {
    private $table = "client";

    /**
     * @GET
     * @uri /addview
     */
    public function addview(){
        $params = $this->reqInfo->params();
        $db = MedooFactory::getInstance();

        if(isset($params['client_id'])){
            $db->update($this->table, [
                'client_view[+]'=> 1
            ], [
                'client_id'=> $params['client_id']
            ]);
        }
        $date = Date("Y-m-d");
        $clientDate = $db->get("client_date", "*", ["AND"=> [
            'client_id'=> $params['client_id'],
            'date'=> $date
            ]]);
        if(!$clientDate){
            $db->insert("client_date", [
                'client_id'=> $params['client_id'],
                'date'=> $date,
                'view'=> 1
                ]);
        }
        else {
            $db->update('client_date', [
                'view[+]'=> 1
            ],
            ["AND"=> [
                'client_id'=> $params['client_id'],
                'date'=> $date
            ]]);
        }

        return ['success'=> true];
    }

    /**
     * @GET
     */
    public function gets(){
        $params = $this->reqInfo->params();
        $db = MedooFactory::getInstance();

        $data = $db->select($this->table, "*");

        return [
            "length"=> count($data),
            "data"=> $data
        ];
    }
}