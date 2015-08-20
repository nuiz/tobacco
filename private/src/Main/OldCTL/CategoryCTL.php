<?php
/**
 * Created by PhpStorm.
 * User: NUIZ
 * Date: 29/1/2558
 * Time: 11:47
 */

namespace Main\CTL;
use Main\Service\CategoryService;
use Main\Service\CategoryService\CategoryServiceException;
use Main\View\JsonView;
use Main\Helper\ResponseHelper;
use Main\Log\Log;

/**
 * @Restful
 * @uri /masterCategory
 */
class CategoryCTL extends BaseCTL {
    /**
     * @GET
     */
    public function gets(){
        $params = $this->reqInfo->params();
        try {
            $item = CategoryService::getInstance()->gets($params, $this->getCtx());
            return new JsonView($item);
        }
        catch (CategoryServiceException $e){
            Log::error($e);
            return new JsonView(ResponseHelper::error('Error'));
        }
    }
}