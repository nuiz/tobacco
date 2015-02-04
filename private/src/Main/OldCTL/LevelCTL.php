<?php
/**
 * Created by PhpStorm.
 * User: NUIZ
 * Date: 29/1/2558
 * Time: 11:47
 */

namespace Main\CTL;
use Main\Service\LevelService;
use Main\View\JsonView;
use Main\Helper\ResponseHelper;
use Main\Service\LevelService\LevelServiceException;
use Main\Log\Log;

/**
 * @Restful
 * @uri /masterLevel
 */
class LevelCTL extends BaseCTL {
    /**
     * @GET
     */
    public function gets(){
        $params = $this->reqInfo->params();
        try {
            $item = LevelService::getInstance()->gets($params);
            return new JsonView($item);
        }
        catch (LevelServiceException $e){
            Log::error($e);
            return new JsonView(ResponseHelper::error('Error'));
        }
    }
}