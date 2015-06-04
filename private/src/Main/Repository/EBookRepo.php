<?php
/**
 * Created by PhpStorm.
 * User: NUIZ
 * Date: 9/3/2558
 * Time: 9:51
 */

namespace Main\Repository;


use Main\DAO\ListDAO;
use Main\DB\Medoo\Medoo;
use Main\Helper\URL;

class EBookRepo {
    /**
     * @var Medoo $db;
     */
    private $db, $table = "content",
        $join = [
            "[>]content_book"=> ["content_id"],
            "[>]book_type"=> ["book_type_id"]
        ];
    public function setDB(Medoo $db){
        $this->db = $db;
    }

    public function gets($params){
        $params["url"] = URL::absolute("/ebook");
        $params["field"] = "*";
        $params["join"] = $this->join;
        $params["where"] = [
            "content_type"=> "book",
            "ORDER"=> "content_id DESC",
            "LIMIT"=> 1000
        ];
        $listResponse = ListDAO::gets($this->table, $params);
        return $listResponse;
    }

    public function getsRandom($params){
        $params["url"] = URL::absolute("/ebook");
        $params["field"] = "*";
        $params["join"] = $this->join;
        $params["where"] = [
            "content_type"=> "book",
            "ORDER"=> "RAND()",
            "LIMIT"=> 1000
        ];
        $listResponse = ListDAO::gets($this->table, $params);
        return $listResponse;
    }

    public function getByTypeId($typeId){
        $params["url"] = URL::absolute("/ebook?book_type_id=".$typeId);
        $params["field"] = "*";
        $params["join"] = $this->join;
        $params["where"] = [
            "AND"=>[
                "content.content_type"=> "book",
                "content_book.book_type_id"=> $typeId,
            ],
            "ORDER"=> "content_id DESC",
            "LIMIT"=> 1000
        ];
        $listResponse = ListDAO::gets($this->table, $params);
        return $listResponse;
    }
}