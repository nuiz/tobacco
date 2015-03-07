<?php
/**
 * Created by PhpStorm.
 * User: NUIZ
 * Date: 7/3/2558
 * Time: 17:09
 */

namespace Test\CTL;
use Main\CTL\ConfigCTL;
use Main\Repository\ConfigSystemRepository;
use Main\DB\Medoo\Medoo;
use Main\Http\RequestInfo;

class ConfigCTLTest extends \PHPUnit_Framework_TestCase {
    public function testHasClass(){
        $ctl = new ConfigCTL();
    }

    public function testGet(){
        $name = "config_name";
        $params = [$name=> "config_extension"];

        $item = ["config_extension"=> "pdf, jpeg"];

        $reqMock = \Mockery::mock('Main\Http\RequestInfo');
        $reqMock->shouldReceive("params")->andReturn($params);

        $repoMock = \Mockery::mock('Main\Repository\ConfigSystemRepository');
        $repoMock->shouldReceive("get")->with($params[$name])->andReturn($item);

        $ctl = new ConfigCTL();
        $ctl->setReqInfo($reqMock);
        $ctl->setRepo($repoMock);

        $res = $ctl->get();
        $this->assertEquals($item, $res);
    }

    public function testGetAll(){
        $name = "config_name";
        $params = [];

        $items = [
            ["config_extension"=> "pdf, jpeg"]
        ];

        $reqMock = \Mockery::mock('Main\Http\RequestInfo');
        $reqMock->shouldReceive("params")->andReturn($params);

        $repoMock = \Mockery::mock('Main\Repository\ConfigSystemRepository');
        $repoMock->shouldReceive("getAll")->andReturn($items);

        $ctl = new ConfigCTL();
        $ctl->setReqInfo($reqMock);
        $ctl->setRepo($repoMock);

        $res = $ctl->get();
        $this->assertEquals($items, $res);
    }
}
