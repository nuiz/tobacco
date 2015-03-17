<?php
/**
 * Created by PhpStorm.
 * User: NUIZ
 * Date: 11/3/2558
 * Time: 14:31
 */

namespace Main\Repository;


use Main\DAO\ListDAO;
use Main\DB\Medoo\Medoo;
use Main\Helper\ArrayHelper;
use Main\Helper\URL;

class FAQRepo {
    /**
     * @var Medoo $db;
     */
    private $db, $table = "faq";

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

    public function add($params){
        $insert = ArrayHelper::filterKey(['faq_question', 'faq_answer'], $params);
        $id = $this->db->insert($this->table, $insert);

        return $this->_get($id);
    }

    public function edit($id, $params){
        $update = ArrayHelper::filterKey(['faq_question', 'faq_answer'], $params);
        $id = $this->db->update($this->table, $update, ["faq_id"=> $id]);

        return $this->_get($id);
    }

    public function gets($params){
        $params["url"] = URL::absolute("/faq");
        $params["field"] = "*";
        $params["where"] = [
            "ORDER"=> "faq_id DESC",
            "LIMIT"=> 1000
        ];
        $listResponse = ListDAO::gets($this->table, $params);
        return $listResponse;
    }

    public function delete($id){
        $this->db->delete($this->table, ["faq_id"=> $id]);
        return ["success"=> true];
    }

    public function _get($id){
        return $this->db->get($this->table, "*", ["faq_id"=> $id]);
    }
}