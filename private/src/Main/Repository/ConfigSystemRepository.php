<?php
/**
 * Created by PhpStorm.
 * User: NUIZ
 * Date: 7/3/2558
 * Time: 15:34
 */

namespace Main\Repository;

use Main\DB\Medoo\Medoo;

class ConfigSystemRepository {
    /**
     * @var Medoo $db;
     */
    private $db, $table = "config_system";
    public function setDB(Medoo $db){
        $this->db = $db;
    }

    public function update($params){
        $res = [];
        foreach($params as $key=> $value){
            $this->db->update($this->table, ['config_value'=> $value], ['config_name'=> $key]);
            $res[] = [$key=> $value];
        }
        return $res;
    }

    public function get($name){
        return $this->db->get($this->table, "*", ["config_name"=> $name]);
    }

    public function getAll(){
        $item = $this->db->select($this->table, "*");
    }
}