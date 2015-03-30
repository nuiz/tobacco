<?php
/**
 * Created by PhpStorm.
 * User: NUIZ
 * Date: 30/3/2558
 * Time: 15:21
 */

namespace Main\Repository;


use Main\CTL\BaseCTL;
use Main\DAO\ListDAO;
use Main\DB\Medoo\Medoo;
use Main\Helper\URL;

class BookPlaceRepo extends BaseCTL {
    /**
     * @var Medoo $db;
     */
    private $db, $table = "book_place";

    /**
     * @return Medoo
     */
    public function getDb()
    {
        return $this->db;
    }

    /**
     * @param Medoo $db
     */
    public function setDb($db)
    {
        $this->db = $db;
    }

    public function gets(){
        $params["url"] = URL::absolute("/book_place");
        $params["field"] = "*";
        $params["where"] = [
            "ORDER"=> "book_place_id DESC",
            "LIMIT"=> 1000
        ];
        $listResponse = ListDAO::gets($this->table, $params);
        return $listResponse;
    }
}