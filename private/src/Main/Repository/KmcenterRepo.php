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
use Main\Helper\ResponseHelper;
use Main\Helper\URL;
use Main\Http\FileUpload;
use Valitron\Validator;

class KmcenterRepo {
    /**
     * @var Medoo $db;
     */
    private $db, $table = "kmcenter", $map_path = "public/kmcenter_map/";

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

    public function add($params, $files){
        $insert = ArrayHelper::filterKey(['kmcenter_name', 'kmcenter_description'], $params);
        if(!isset($files['kmcenter_map_pic'])
            || !is_array($files['kmcenter_map_pic'])
            || count($files['kmcenter_map_pic']) == 0
            || empty($files['kmcenter_map_pic']['tmp_name'])){
            return ResponseHelper::error('require kmcenter_map_pic');
        }
        else {
            $mapUploaded = FileUpload::load($files['kmcenter_map_pic']);

            $newName = $mapUploaded->generateName(true);
            $mapUploaded->move($this->map_path.$newName);
            $insert['kmcenter_map_pic'] = $newName;
        }
        $id = $this->db->insert($this->table, $insert);

        return $this->_get($id);
    }

    public function edit($id, $params, $files){
        $update = ArrayHelper::filterKey(['kmcenter_name', 'kmcenter_name'], $params);
        if(isset($files['kmcenter_map_pic'])){
            $mapUploaded = FileUpload::load($files['kmcenter_map_pic']);
            if($mapUploaded->isUploaded()){
                $newName = $mapUploaded->generateName(true);
                $mapUploaded->move($this->map_path.$newName);
                $update['kmcenter_map_pic'] = $newName;
            }
        }

        $this->db->update($this->table, $update, ["kmcenter_id"=> $id]);

        return $this->_get($id);
    }

    public function gets($params){
        $params["url"] = URL::absolute("/kmcenter");
        $params["field"] = "*";
        $params["where"] = [
            "ORDER"=> "kmcenter_id DESC",
            "LIMIT"=> 1000
        ];
        $listResponse = ListDAO::gets($this->table, $params);
        $this->_builds($listResponse['data']);
        return $listResponse;
    }

    public function delete($id){
        $this->db->delete($this->table, ["kmcenter_id"=> $id]);
        return ["success"=> true];
    }

    public function _get($id){
        $item = $this->db->get($this->table, "*", ["kmcenter_id"=> $id]);
        $this->_build($item);
        return $item;
    }

    public function _build(&$item){
        $item["kmcenter_map_pic_url"] = URL::absolute("/").$this->map_path.$item["kmcenter_map_pic"];
    }

    public function _builds(&$items){
        foreach($items as $key=> $item){
            $this->_build($items[$key]);
        }
    }
}