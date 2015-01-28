<?php
/**
 * Created by PhpStorm.
 * User: NUIZ
 * Date: 14/1/2558
 * Time: 9:36
 */

namespace Main\CTL;
use Main\Service\AccountService;
use Main\View\JsonView;


/**
 * @Restful
 * @uri /test
 */
class TestCTL {
    /**
     * @GET
     */
    public function put(){
        $test = AccountService::getInstance()->gets(['page'=> 2]);
        return new JsonView($test);
    }
}