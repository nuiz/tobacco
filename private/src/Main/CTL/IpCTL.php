<?php
/**
 * Created by PhpStorm.
 * User: NUIZ
 * Date: 21/5/2558
 * Time: 16:26
 */

namespace Main\CTL;
use Main\DAO\ListDAO;
use Main\DB\Medoo\MedooFactory;
use Main\Helper\ArrayHelper;

/**
 * @Restful
 * @uri /ip
 */

class IpCTL extends BaseCTL {
    private $table = "ip_filter";
    /**
     * @GET
     */
    public function gets(){
        $options = $_GET;

        $list = ListDAO::gets($this->table, $options);

        return $list;
    }

    /**
     * @POST
     */
    public function add(){
        $db = MedooFactory::getInstance();

        $insert = ArrayHelper::filterKey(['ip'], $this->getReqInfo()->params());
        $id = $db->insert($this->table, $insert);

        return $this->_get($id);
    }

    /**
     * @DELETE
     * @uri /[i:id]
     */
    public function delete(){
        $db = MedooFactory::getInstance();
        $db->delete($this->table, ["id"=> $this->getReqInfo()->urlParam("id")]);

        return ['success'=> true];
    }

    public function _get($id){
        $db = MedooFactory::getInstance();
        return $db->get($this->table, "*", ["id"=> $id]);
    }
}