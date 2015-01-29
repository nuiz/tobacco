<?php
/**
 * Created by PhpStorm.
 * User: NUIZ
 * Date: 29/1/2558
 * Time: 11:47
 */

namespace Main\CTL;
use Main\Service\DivisionService;
use Main\Service\DivisionService\DivisionServiceException;
use Main\View\JsonView;
use Main\Helper\ResponseHelper;
use Main\Log\Log;

/**
 * @Restful
 * @uri /masterDivision
 */
class DivisionCTL extends BaseCTL {
    /**
     * @GET
     */
    public function gets(){
        $params = $this->reqInfo->params();
        try {
            $item = DivisionService::getInstance()->gets($params);
            return new JsonView($item);
        }
        catch (DivisionServiceException $e){
            Log::error($e);
            return new JsonView(ResponseHelper::error('Error'));
        }
    }
}