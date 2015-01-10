<?php
/**
 * Created by PhpStorm.
 * User: NUIZ
 * Date: 9/1/2558
 * Time: 10:08
 */

namespace Main\CTL;
use Main\View\JsonView;
use Main\Service\NewsService;
use Main\Exception\Service\ServiceException;

/**
 * @Restful
 * @uri /news
 */
class NewsCTL extends BaseCTL {
    /**
     * @POST
     */
    public function add(){
        try {
            $item = NewsService::getInstance()->add($this->reqInfo->params(), $this->getCtx());
            $v = new JsonView($item);
            return $v;
        }
        catch (ServiceException $e){
            return $e->getResponse();
        }
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
        try {
            $item = NewsService::getInstance()->gets($this->reqInfo->params(), $this->getCtx());
            $v = new JsonView($item);
            return $v;
        }
        catch (ServiceException $e){
            return $e->getResponse();
        }
    }

    /**
     * @DELETE
     * @uri /[:id]
     */
    public function delete(){
        try {
            $res = NewsService::getInstance()->delete($this->reqInfo->urlParam('id'), $this->getCtx());
            $v = new JsonView(['success'=> $res]);
            return $v;
        }
        catch (ServiceException $e){
            return $e->getResponse();
        }
    }
}