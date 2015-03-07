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

    public function update($name, $value){
        return $this->db->update($this->table, ['config_value'=> $value], ['config_name'=> $name]);
    }

    public function get($name){
        return $this->db->get($this->table, "*", ["config_name"=> $name]);
    }

    public function getAll(){
        return $this->db->select($this->table, "*");
    }
}