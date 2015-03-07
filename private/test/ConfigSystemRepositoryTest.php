<?php
/**
 * Created by PhpStorm.
 * User: NUIZ
 * Date: 7/3/2558
 * Time: 15:35
 */

namespace Test;

use Main\DB\Medoo\MedooFactory;
use Main\Repository\ConfigSystemRepository;

class ConfigSystemRepositoryTest extends \PHPUnit_Framework_TestCase {
    public function tearDown(){
        \Mockery::close();
    }

    public function testUpdate(){
        $dbMock = \Mockery::mock('Main\DB\Medoo\Medoo');
        $name = "extension_upload";
        $value = "jpeg,png,mp4";

        $update = ["config_value"=> $value];
        $where = ["config_name"=> $name];

        $dbMock->shouldReceive('update')->with("config_system", $update, $where)->andReturn(1);

        $repo = new ConfigSystemRepository();
        $repo->setDB($dbMock);

        $res = $repo->update($name, $value);
        $this->assertInternalType("int", $res);
    }

    public function testGet(){
        $dbMock = \Mockery::mock('Main\DB\Medoo\Medoo');
        $name = "extension_upload";
        $value = "jpeg,png,mp4";
        $where = ["config_name"=> "extension_upload"];
        $dbMock->shouldReceive('get')->with("config_system", "*", $where)->andReturn($value);

        $repo = new ConfigSystemRepository();
        $repo->setDB($dbMock);

        $res = $repo->get($name);
        $this->assertEquals($value, $res);
    }

    public function testGetAll(){
        $rows = [
            ["config_name"=> "extension_upload", "config_value"=> "jpeg,png,mp4"]
        ];

        $dbMock = \Mockery::mock('Main\DB\Medoo\Medoo');
        $dbMock->shouldReceive('select')->with("config_system", "*")->andReturn($rows);

        $repo = new ConfigSystemRepository();
        $repo->setDB($dbMock);

        $res = $repo->getAll();
        $this->assertEquals($rows, $res);
    }
}
