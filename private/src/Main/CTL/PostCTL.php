<?php
/**
 * Created by PhpStorm.
 * User: NUIZ
 * Date: 20/5/2558
 * Time: 16:38
 */

namespace Main\CTL;


use Main\DB\Medoo\Medoo;
use Main\DB\Medoo\MedooFactory;
use Main\Helper\ArrayHelper;
use Main\Helper\ResponseHelper;
use Main\Http\FileUpload;
use Main\Service\BlogService;

/**
 * @Restful
 * @uri /blog
 */
class PostCTL extends BaseCTL {
    private $blogService = null;
    /**
     * @GET
     * @uri /feed
     */
    public function gets(){
        return $this->getService()->gets();
    }

    /**
     * @GET
     * @uri /user/[i:id]
     */
    public function gets2(){
        $uid = $this->getReqInfo()->urlParam("id");
        return $this->getService()->gets2($uid);
    }

    /**
     * @GET
     * @uri /[i:id]
     */
    public function get(){
        $uid = $this->getReqInfo()->urlParam("id");
        return $this->getService()->get($uid);
    }

    /**
     * @POST
     * @uri /post
     */
    public function add(){
        $params = $this->getReqInfo()->params();
        if($params["post_type"]=="video"){
            $params["post_video"] = FileUpload::load($this->getReqInfo()->file("post_video"));
            if(is_null($params["post_video"])){
                return ResponseHelper::validateError(["post_video"=> "require upload video"]);
            }
        }
        if($params["post_type"]=="image"){
            $params["post_image"] = [];
            $images = $this->getReqInfo()->file("post_image");
            if(is_null($images) && count($images["name"]) == 0){
                return ResponseHelper::validateError(["post_image"=> "require upload image"]);
            }

            foreach($images["name"] as $key=> $name){
                $params["post_image"][] = FileUpload::load([
                    "tmp_name"=> $images["tmp_name"][$key],
                    "name"=> $name
                ]);
            }
        }
        return $this->getService()->add($params, $this->getReqInfo()->getAuthAccount());
    }

    /**
     * @DELETE
     * @GET
     * @uri /post/delete/[i:id]
     */
    public function delete(){
        $id = $this->getReqInfo()->urlParam("id");
        return $this->getService()->deletePost($id);
    }

    /**
     * @DELETE
     * @GET
     * @uri /post/comment/delete/[i:id]
     */
    public function deleteComment(){
        $id = $this->getReqInfo()->urlParam("id");
        return $this->getService()->deleteComment($id);
    }

    /**
     * @POST
     * @uri /post/like/[i:id]
     */
    public function like(){
        $id = $this->getReqInfo()->urlParam("id");
        $this->getService()->like($id, $this->getReqInfo()->getAuthAccount());

        return $this->getService()->get($id);
    }

    /**
     * @POST
     * @uri /post/unlike/[i:id]
     */
    public function unlike(){
        $id = $this->getReqInfo()->urlParam("id");
        $this->getService()->unlike($id, $this->getReqInfo()->getAuthAccount());

        return $this->getService()->get($id);
    }

    /**
     * @POST
     * @uri /post/comment/[i:id]
     */
    public function addComment(){
        return $this->getService()->addComment($this->getReqInfo()->urlParam("id"), $this->getReqInfo()->params(), $this->getReqInfo()->getAuthAccount());
    }

    /**
     * @GET
     * @uri /post/comment/[i:id]
     */
    public function getComments(){
        return $this->getService()->getComments($this->getReqInfo()->urlParam("id"));
    }

    public function getService(){
        if(is_null($this->blogService)){
            $this->blogService = new BlogService();
            $this->blogService->setDb(MedooFactory::getInstance());
            $this->blogService->setAuthUser($this->getReqInfo()->getAuthAccount());
        }

        return $this->blogService;
    }
}