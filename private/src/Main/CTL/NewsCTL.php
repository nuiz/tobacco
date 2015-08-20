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
        // $insert = ArrayHelper::filterKey(["news_name", "news_description"], $params);
        $insert = ArrayHelper::filterKey(["news_name", "news_description", "created_at"], $params);

        $user = $this->reqInfo->getAuthAccount();
        AccountPermission::requirePermission($user, [AccountPermission::ID_CLUSTER_IT, AccountPermission::ID_SUPER_ADMIN, AccountPermission::ID_WRITER]);

        $insert["account_id"] = $user["account_id"];
        //$insert["created_at"] = date("Y-m-d H:i:s");
        $insert["created_at"] = $insert["created_at"].date(" H:i:s");

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
            $update = ArrayHelper::filterKey(["news_name", "news_description", "created_at"], $params);

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
     * @uri /[i:id]
     */
    public function get(){
        try {
            $id = $this->reqInfo->urlParam('id');
            $item = NewsService::getInstance()->get($id, $this->getCtx());
            $db = MedooFactory::getInstance();
            if($item){
                $this->_build($item);
                $db->update($this->table, ["view_count[+]"=> 1], ["news_id"=> $id]);
            }
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
            $params["where"]["AND"]["created_at[<>]"] = [date("Y-m-01", $ts), date("Y-m-t", $ts)];
        }

        if(isset($params["auth_token"]) && !empty($params["auth_token"]) && isset($params["req_from_management"])){
            $user = $this->reqInfo->getAuthAccount();
            $params["where"]["AND"]["news.account_id"] = $user["account_id"];
        }

        $listResponse = ListDAO::gets($this->table, $params);
        $this->_builds($listResponse["data"]);
        return new JsonView($listResponse);
    }

    /**
     * @GET
     * @uri /by_year
     */
    public function gets_by_year(){
        $params = $this->reqInfo->params();
        $params["url"] = URL::absolute("/news");
        $params["where"] = [
            "ORDER"=> "created_at DESC"
        ];

        $date = $params["year"];
        $ts = strtotime($date);

        $db = MedooFactory::getInstance();
        $items = $db->select("news", "*", ["AND"=> [
            "created_at[>]"=> date("Y-01-01 00:00:00", $ts),
            "created_at[<]"=> date("Y-12-t 23:59:59", strtotime(date("Y-12-01", $ts)))
        ]]);

        $res = [];
        for($i=0; $i<12; $i++){
            $res[$i] = [
                "length"=> 0,
                "data"=> []
            ];
            foreach($items as $item){
                $m = $i+1;
                $itemTs = strtotime($item["created_at"]);
                $startMothTs = strtotime(date("Y-{$m}-01 00:00:00", $ts));
                $endMothTs = strtotime(date("Y-{$m}-t 23:59:59", strtotime(date("Y-{$m}-01", $ts))));
                if($itemTs >= $startMothTs && $itemTs <= $endMothTs){
                    $this->_build($item, false);
                    $res[$i]["data"][] = $item;
                }
            }
            $res[$i]["length"] = count($res[$i]["data"]);
        }

        return new JsonView([
            "length"=> count($items),
            "data"=> $res
        ]);
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

    /**
     * @DELETE
     * @uri /[i:id]/images
     */
    public function imagesDelete(){
        $news_id = $this->reqInfo->urlParam("id");
        $params = $this->reqInfo->inputs();
        $db = MedooFactory::getInstance();
        $db->delete($this->table_images, ["id"=> $params["id"]]);

        return ['success'=> true];
    }

    // internal function
    public function _get($id){
        $db = MedooFactory::getInstance();
        $item = $db->get($this->table, "*", ["news_id"=> $id]);
        if($item) {
            $this->_build($item);
            $db->update($this->table, ["view_count[+]"=> 1], ["news_id"=> $id]);
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

    public function _build(&$item, $with_images = true){
        $item["news_cover_url"] = URL::absolute("/public/news_image/".$item["news_cover_path"]);
        if($with_images){
            $db = MedooFactory::getInstance();
            $item["news_images"] = $db->select($this->table_images, "*", ["news_id"=> $item["news_id"]]);
            foreach($item["news_images"] as $key=> $value){
                $item["news_images"][$key]["image_url"] = URL::absolute("/public/news_image/".$value["image_path"]);
            }
        }
    }
}