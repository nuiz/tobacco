<?php
/**
 * Created by PhpStorm.
 * User: NUIZ
 * Date: 9/1/2558
 * Time: 10:08
 */

namespace Main\CTL;
use Main\DAO\ListDAO;
use Main\DB\Medoo\MedooFactory;
use Main\Helper\ArrayHelper;
use Main\Helper\URL;
use Main\Http\FileUpload;
use Main\Permission\AccountPermission;
use Main\View\JsonView;
use Main\Service\NewsService;
use Main\Exception\Service\ServiceException;

/**
 * @Restful
 * @uri /news
 */
class NewsCTL extends BaseCTL {
    private $table = "news";
    /**
     * @POST
     */
    public function add(){
        $params = $this->reqInfo->params();
        $insert = ArrayHelper::filterKey(["news_name", "news_description"], $params);

        $user = $this->reqInfo->getAuthAccount();
        AccountPermission::requirePermission($user, [AccountPermission::ID_CLUSTER_IT, AccountPermission::ID_SUPER_ADMIN, AccountPermission::ID_WRITER]);

        $insert["account_id"] = $user["account_id"];

        $img = FileUpload::load($this->reqInfo->file("news_image"));
        $name = $img->generateName(true);
        $des = "public/news_image/".$name;
        $img->move($des);
        $insert['news_image_path'] = $name;

        $db = MedooFactory::getInstance();
        $id = $db->insert($this->table, $insert);

        return $this->_get($id);
    }

    /**
     * @PUT
     * @uri /[:id]
     */
    public function edit(){
        try {
            $item = NewsService::getInstance()->edit($this->reqInfo->urlParam('id'), $this->reqInfo->params(), $this->getCtx());
            $v = new JsonView($item);
            return $v;
        }
        catch (ServiceException $e){
            return $e->getResponse();
        }
    }

    /**
     * @GET
     * @uri /[:id]
     */
    public function get(){
        try {
            $item = NewsService::getInstance()->get($this->reqInfo->urlParam('id'), $this->getCtx());
            $v = new JsonView($item);
            return $v;
        }
        catch (ServiceException $e){
            return $e->getResponse();
        }
    }

    /**
     * @GET
     */
    public function gets(){
        $params = $this->reqInfo->params();
        $params["url"] = URL::absolute("/news");
        $params["where"] = [
            "ORDER"=> "news_id DESC"
        ];

        $listResponse = ListDAO::gets($this->table, $params);
        $this->_builds($listResponse["data"]);
        return new JsonView($listResponse);
    }

    /**
     * @DELETE
     * @uri /[:id]
     */
    public function delete(){
        $id = $this->reqInfo->urlParam("id");
        $db = MedooFactory::getInstance();
        $db->delete($this->table, ["news_id"=> $id]);
        return ["success"=> true];
    }

    // internal function
    public function _get($id){
        $db = MedooFactory::getInstance();
        $item = $db->get($this->table, "*", ["news_id"=> $id]);
        if($item) {
            $this->_build($item);
            return $item;
        }
        else {
            return null;
        }
    }

    public function _builds(&$items){
        foreach($items as $key=> $item){
            $this->_build($items[$key]);
        }
    }

    public function _build(&$item){
        $item["news_image_url"] = URL::absolute("/public/news_image/".$item["news_image_path"]);
    }
}