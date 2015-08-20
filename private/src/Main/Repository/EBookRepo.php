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

    public function getsSearch($params){
        $keyword = $params["keyword"];
        $jKeyword = trim(json_encode($keyword), '"');
        $sql = "SELECT * FROM content_book LEFT JOIN content ON content_book.content_id=content.content_id";
        $sql .= " WHERE (content.content_name LIKE ?";
        $sql .= " OR content_book.book_author LIKE ?";
        $sql .= " OR content_book.book_places LIKE ?)";
        $sql .= " AND content.content_type = 'book'";
        $sql .= " ORDER BY content.content_id DESC";

        $pdo = $this->db->pdo;
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            '%'.$keyword.'%',
            '%'.$keyword.'%',
            '%'.$jKeyword.'%'
            ]);
        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $sql = str_replace("*", "COUNT(*) as c", $sql);
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            '%'.$keyword.'%',
            '%'.$keyword.'%',
            '%'.$jKeyword.'%'
            ]);
        $total = $stmt->fetchColumn();
        return [
            'total'=> $total,
            'length'=> count($data),
            'data'=> $data
        ];
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
