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
use Main\Helper\ResponseHelper;
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
    private $table = "news", $table_images = "news_image";
    /**
     * @POST
     */
    public function add(){
        $params = $this->reqInfo->params();
        $insert = ArrayHelper::filterKey(["news_name", "news_description"], $params);

        $user = $this->reqInfo->getAuthAccount();
        AccountPermission::requirePermission($user, [AccountPermission::ID_CLUSTER_IT, AccountPermission::ID_SUPER_ADMIN, AccountPermission::ID_WRITER]);

        $insert["account_id"] = $user["account_id"];
        $insert["created_at"] = date("Y-m-d H:i:s");

        $img = FileUpload::load($this->reqInfo->file("news_cover"));
        $name = $img->generateName(true);
        $des = "public/news_image/".$name;
        $img->move($des);
        $insert['news_cover_path'] = $name;

        $db = MedooFactory::getInstance();
        $db->pdo->beginTransaction();
        $id = $db->insert($this->table, $insert);

        $images = @$_FILES["news_images"]? $_FILES["news_images"]: [];
        foreach($images["name"] as $key=> $value){
            $name = $images["name"][$key];
            $ext = explode(".", $name);
            $ext = array_pop($ext);
            if(!in_array($ext, ['jpg', 'jpeg', 'png'])){
                $db->pdo->rollBack();
                return ResponseHelper::error("news_image extension not allowed");
            }
            $this->insertImage($images["tmp_name"][$key], $name, $id);
        }

        $db->pdo->commit();

        return $this->_get($id);
    }

    public function insertImage($tmp_name, $name, $news_id){
        $db = MedooFactory::getInstance();
        $id = $db->insert($this->table_images, ['news_id'=> $news_id]);

        $img = FileUpload::load(['tmp_name'=> $tmp_name, 'name'=> $name]);
        $newName = $img->generateName(true);
        $des = "public/news_image/".$newName;
        $img->move($des);

        $db->update($this->table_images, ['image_path'=> $newName], ['id'=> $id]);
    }

    /**
     * @PUT
     * @uri /[:id]
     */
    public function edit(){
        try {
            $params = $this->reqInfo->params();
            $update = ArrayHelper::filterKey(["news_name", "news_description"], $params);

            $file = $this->reqInfo->file("news_cover");

            if(!is_null($file)){
                $img = FileUpload::load($this->reqInfo->file("news_cover"));
                $name = $img->generateName(true);
                $des = "public/news_image/".$name;
                $img->move($des);
                $update['news_cover_path'] = $name;
            }

            $id = $this->reqInfo->urlParam('id');
            $db = MedooFactory::getInstance();
            $db->update($this->table, $update, ['news_id'=> $id]);

            return $this->_get($id);
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
            if($item) $this->_build($item);
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
            "ORDER"=> "created_at DESC"
        ];

        $date = $this->reqInfo->param("date");
        if(!empty($date)){
            $ts = strtotime($date);
            $params["where"]["created_at[<>]"] = [date("Y-m-01", $ts), date("Y-m-t", $ts)];
        }

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
        $db->delete($this->table_images, ["news_id"=> $id]);
        return ["success"=> true];
    }

    /**
     * @POST
     * @uri /[i:id]/images
     */
    public function imagesUpload(){
        $id = $this->reqInfo->urlParam("id");
        $db = MedooFactory::getInstance();
        $db->pdo->beginTransaction();
        $images = @$_FILES["news_images"]? $_FILES["news_images"]: [];
        foreach($images["name"] as $key=> $value){
            $name = $images["name"][$key];
            $ext = explode(".", $name);
            $ext = array_pop($ext);
            if(!in_array($ext, ['jpg', 'jpeg', 'png'])){
                $db->pdo->rollBack();
                return ResponseHelper::error("news_image extension not allowed");
            }
            $this->insertImage($images["tmp_name"][$key], $name, $id);
        }

        $db->pdo->commit();
        return $this->_get($id);
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
        $item["news_cover_url"] = URL::absolute("/public/news_image/".$item["news_cover_path"]);
        $db = MedooFactory::getInstance();
        $item["news_images"] = $db->select($this->table_images, "*", ["news_id"=> $item["news_id"]]);
        foreach($item["news_images"] as $key=> $value){
            $item["news_images"][$key]["image_url"] = URL::absolute("/public/news_image/".$value["image_path"]);
        }
    }
}