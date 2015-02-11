<?php
/**
 * Created by PhpStorm.
 * User: NUIZ
 * Date: 29/1/2558
 * Time: 11:47
 */

namespace Main\CTL;
use Main\DAO\ListDAO;
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
}