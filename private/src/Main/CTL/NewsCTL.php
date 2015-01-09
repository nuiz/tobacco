<?php
/**
 * Created by PhpStorm.
 * User: NUIZ
 * Date: 9/1/2558
 * Time: 10:08
 */

namespace Main\CTL;

/**
 * @Restful
 * @uri /news
 */
class NewsCTL extends BaseCTL {
    /**
     * @GET
     */
    public function test(){
        return [];
    }
}