<?php
/**
 * Created by PhpStorm.
 * User: NUIZ
 * Date: 7/3/2558
 * Time: 16:50
 */

namespace Main\Repository;


use Main\DAO\ListDAO;
use Main\DB\Medoo\Medoo;
use Main\Helper\ArrayHelper;
use Main\Helper\ResponseHelper;
use Main\Helper\URL;
use Main\View\JsonView;

class GuruRepo {
    /**
     * @var Medoo $db;
     */
    private $db;
    const TABLE_GURU = "guru", TABLE_ACCOUNT = "account", TABLE_GURU_CAT = "guru_category";
    private $join = [
        "[>]guru_category"=> ["guru_cat_id"=> "guru_cat_id"],
        "[>]account"=> ["account_id"=> "account_id"]
    ];

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
        $insert = ArrayHelper::filterKey(['account_id', 'guru_cat_id', 'guru_history', 'guru_telephone'], $params);
        if($this->db->count(self::TABLE_ACCOUNT, ['account_id'=> $params['account_id']]) == 0){
            return ResponseHelper::error('not found account', 601);
        }
        if($this->db->count(self::TABLE_GURU_CAT, ['guru_cat_id'=> $params['guru_cat_id']]) == 0){
            return ResponseHelper::error('not found account', 601);
        }
        $id = $this->db->insert(self::TABLE_GURU, $insert);

        return $this->_get($id);
    }

    public function edit($id, $params){
        $update = ArrayHelper::filterKey(['guru_history', 'guru_telephone'], $params);
        $id = $this->db->update(self::TABLE_GURU, $update, ["guru_id"=> $id]);

        return $this->_get($id);
    }

    public function gets($params){
        $params["url"] = URL::absolute("/guru");
        $params["join"] = $this->join;
        $params["field"] = "*";
        $params["where"] = [
            "ORDER"=> "guru_id DESC",
            "LIMIT"=> 1000
        ];

        if(isset($params["guru_cat_id"]) && !empty($params['guru_cat_id'])){
            $params["where"]['guru.guru_cat_id'] = $params["guru_cat_id"];
        }

        $listResponse = ListDAO::gets(self::TABLE_GURU, $params);
        $this->_builds($listResponse['data']);
        return $listResponse;
    }

    public function getCats($params){
        $params["url"] = URL::absolute("/guru/category");
        $params["field"] = "*";
        $params["where"] = [
            "ORDER"=> "guru_cat_id DESC",
            "LIMIT"=> 1000
        ];
        $listResponse = ListDAO::gets(self::TABLE_GURU_CAT, $params);
        return $listResponse;
    }

    public function delete($id){
        $this->db->delete(self::TABLE_GURU, ["guru_id"=> $id]);
        return ["success"=> true];
    }

    public function _get($id){
        $item = $this->db->get(self::TABLE_GURU, $this->join, "*", ["guru_id"=> $id]);
        $this->_build($item);
        return $item;
    }

    public function _build(&$item){
        unset($item['password']);
        unset($item['auth_token']);
    }

    public function _builds(&$items){
        foreach($items as $key=> $item){
            $this->_build($items[$key]);
        }
    }
}