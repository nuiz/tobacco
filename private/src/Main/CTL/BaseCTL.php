<?php
/**
 * Created by PhpStorm.
 * User: p2
 * Date: 7/15/14
 * Time: 11:27 AM
 */

namespace Main\CTL;


use Main\Context\Context;
use Main\Http\RequestInfo;

class BaseCTL {
    /**
     * @var Context $ctx, RequestInfo $req;
     */
    public $reqInfo, $ctx;
    public function __construct(){
        $this->ctx = new Context();
    }

    /**
     * @return RequestInfo
     */
    public function getReqInfo()
    {
        return $this->reqInfo;
    }

    /**
     * @param RequestInfo $reqInfo
     */
    public function setReqInfo(RequestInfo $reqInfo)
    {
        $this->reqInfo = $reqInfo;
    }

    public function getCtx(){
        return $this->ctx;
    }
}