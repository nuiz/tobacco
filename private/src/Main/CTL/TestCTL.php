<?php
/**
 * Created by PhpStorm.
 * User: NUIZ
 * Date: 14/1/2558
 * Time: 9:36
 */

namespace Main\CTL;
use Main\DB\Medoo\MedooFactory;
use Main\Helper\LangHelper;
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
    public function test(){
        $db = MedooFactory::getInstance();
        $data = $db->select("masterlevel", "*");
        if(!$data){
            print_r($db->error());
            exit();
        }
        foreach($data as $key=> $value){
            $data[$key]['lang'] = LangHelper::marionat_decode($value['levelName']);
        }

        return $data;
    }

    /**
     * @GET
     * @uri /reParseMasterLevel
     */
    public function reParseMasterLevel(){
        $db = MedooFactory::getInstance();
        $data = $db->select("masterlevel", "*");
        if(!$data){
            print_r($db->error());
            exit();
        }
        foreach($data as $key=> $value){
            $levelName = LangHelper::marionat_decode($value['levelName']);
            $db->update("masterlevel", ['levelName'=> $levelName], ['levelId'=> $value['levelId']]);
        }

        return $db->select("masterlevel", "*");
    }

    /**
     * @GET
     * @uri /reParseMasterDivision
     */
    public function reParseMasterDivision(){
        $tableName = "masterdivision";
        $db = MedooFactory::getInstance();
        $data = $db->select($tableName, "*");
        if(!$data){
            print_r($db->error());
            exit();
        }
        foreach($data as $key=> $value){
            $division = LangHelper::marionat_decode($value['division']);
            $db->update($tableName, ['division'=> $division], ['divisionId'=> $value['divisionId']]);
        }

        return $db->select($tableName, "*");
    }

    /**
     * @GET
     * @uri /reParseMasterCategory
     */
    public function reParseMasterCategory(){
        $tableName = "mastercategory";
        $db = MedooFactory::getInstance();
        $data = $db->select($tableName, "*");
        if(!$data){
            print_r($db->error());
            exit();
        }
        foreach($data as $key=> $value){
            $category = LangHelper::marionat_decode($value['category']);
            $db->update($tableName, ['category'=> $category], ['categoryId'=> $value['categoryId']]);
        }

        return $db->select($tableName, "*");
    }
}