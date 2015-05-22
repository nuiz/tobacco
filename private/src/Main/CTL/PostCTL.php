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
     * @POST
     * @uri /post
     */
    public function add(){
        $params = $this->getReqInfo()->params();
        if($params["post_type"]=="video"){
            $params["post_video"] = FileUpload::load($this->getReqInfo()->file("video"));
            $params["post_video_thumb"] = FileUpload::load($this->getReqInfo()->file("video_thumb"));
        }
        return $this->getService()->add($params, $this->getReqInfo()->getAuthAccount());
    }

    /**
     * @POST
     * @uri /post/like/[i:id]
     */
    public function like(){
        $this->getService()->like($this->getReqInfo()->urlParam("id"), $this->getReqInfo()->getAuthAccount());

        return ['success'=> true];
    }

    /**
     * @POST
     * @uri /post/unlike/[i:id]
     */
    public function unlike(){
        $this->getService()->unlike($this->getReqInfo()->urlParam("id"), $this->getReqInfo()->getAuthAccount());

        return ['success'=> true];
    }

    public function getService(){
        if(is_null($this->blogService)){
            $this->blogService = new BlogService();
            $this->blogService->setDb(MedooFactory::getInstance());
        }

        return $this->blogService;
    }
}