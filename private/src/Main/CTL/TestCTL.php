<?php
/**
 * Created by PhpStorm.
 * User: NUIZ
 * Date: 14/1/2558
 * Time: 9:36
 */

namespace Main\CTL;
use Main\Http\ParseInput;


/**
 * @Restful
 * @uri /test
 */
class TestCTL {
    /**
     * @PUT
     */
    public function put(){
        ParseInput::multiPartFormData(file_get_contents('php://input'));
        exit();
    }
}