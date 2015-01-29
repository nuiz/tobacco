<?php
/**
 * Created by PhpStorm.
 * User: NUIZ
 * Date: 29/1/2558
 * Time: 11:52
 */

namespace Main\Helper;


class ServiceHelper {
    public static function getDefaultOptionList(){
        return array(
            'limit'=> 100,
            'page'=> 1
        );
    }
}