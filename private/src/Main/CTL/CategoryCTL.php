<?php
/**
 * Created by PhpStorm.
 * User: NUIZ
 * Date: 29/1/2558
 * Time: 11:47
 */

namespace Main\CTL;
use Main\DAO\ListDAO;
use Main\DB\Medoo\MedooFactory;
use Main\Helper\URL;
use Main\Service\CategoryService;
use Main\Service\CategoryService\CategoryServiceException;
use Main\View\JsonView;

/**
 * @Restful
 * @uri /category
 */
class CategoryCTL extends BaseCTL {
    private $table = "category";

    /**
     * @GET
     */
    public function gets(){
        $params = $this->reqInfo->params();
        $params["url"] = URL::absolute("/category");
        $params["where"] = [
            "ORDER"=> "category_id DESC",
            "parent_id"=> 0
        ];

        if(isset($params["parent_id"])){
            $params["where"]["parent_id"] = $params["parent_id"];
        }

        $listResponse = ListDAO::gets($this->table, $params);
        return new JsonView($listResponse);
    }

    /**
     * @GET
     * @uri /all
     */
    public function getsAll(){
        $params = $this->reqInfo->params();
        $params["url"] = URL::absolute("/category");
        $params["where"] = [
            "ORDER"=> "category_id DESC"
        ];
        $params["limit"] = 200;

        $listResponse = ListDAO::gets($this->table, $params);
        return new JsonView($listResponse);
    }

    /**
     * @GET
     * @uri /tree
     */
    public function getTree(){
        $params = $this->reqInfo->params();
        $params["url"] = URL::absolute("/category");
        $params["where"] = [
            "ORDER"=> "category_id DESC"
        ];

        $db = MedooFactory::getInstance();
        $tree = ['data'=>[], 'length'=> 0];
        $items = $db->select($this->table, "*");
        foreach($items as $key=> $item){
            if($item["parent_id"]==0){
                $item['children'] = [];
                foreach($items as $key2=> $item2){
                    if($item["category_id"] == $item2["parent_id"]){
                        $item2['children'] = [];
                        foreach($items as $key3=> $item3){
                            if($item2["category_id"] == $item3["parent_id"]){
                                $item2['children'][] = $item3;
                            }
                        }
                        $item2["children_length"] = count($item2['children']);
                        $item['children'][] = $item2;
                    }
                }
                $item["children_length"] = count($item['children']);
                $tree['data'][] = $item;
            }
        }
        $tree['length'] = count($tree['data']);
        return new JsonView($tree);
    }

    /**
     * @GET
     * @uri /[i:id]
     */
    public function get(){
        $id = $this->reqInfo->urlParam("id");
        $db = MedooFactory::getInstance();
        $item = $db->get($this->table, "*", ["category_id"=> $id]);

        return new JsonView($item);
    }

}