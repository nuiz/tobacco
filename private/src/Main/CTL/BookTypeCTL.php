<?php
/**
 * Created by PhpStorm.
 * User: NUIZ
 * Date: 26/2/2558
 * Time: 14:17
 */

namespace Main\CTL;
use Main\DAO\ListDAO;
use Main\View\JsonView;
use Main\Helper\URL;
use Main\DB\Medoo\MedooFactory;

/**
 * @Restful
 * @uri /book_type
 */
class BookTypeCTL extends BaseCTL {
    private $table = "book_type";

    /**
     * @GET
     */
    public function gets(){
        $params = $this->reqInfo->params();
        $params["url"] = URL::absolute("/book_type");
        $params["where"] = [
            "ORDER"=> "book_type_id DESC"
        ];

        $listResponse = ListDAO::gets($this->table, $params);
        return new JsonView($listResponse);
    }

    /**
     * @GET
     * @uri /[i:id]
     */
    public function get(){
        $id = $this->reqInfo->urlParam("id");
        $db = MedooFactory::getInstance();
        $item = $db->get($this->table, "*", ["book_type_id"=> $id]);

        return new JsonView($item);
    }
}