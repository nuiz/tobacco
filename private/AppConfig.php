<?php
/**
 * Created by PhpStorm.
 * User: MRG
 * Date: 11/5/14 AD
 * Time: 4:36 PM
 */

$configs = array(
    "application" => array(
        "name" => "",
        "title" => "",
        "version" => "",
        "base_url" => "http://192.168.100.26/tobacco",
        "site_url" => "",
        "share_url" => "",
        "directory" => dirname(__FILE__),
        "timezone"=> "Asia/Bangkok",
        "view" => "default"
    ),
    "route"=> array(
        "base_path"=> "/tobacco"
    ),
    "db" => array(
        "mongodb" => array(
            "host" => "localhost",
            "name" => "",
            "user" => "",
            "password" => "",
            "database" => ""
        ),
        "mysql" => array(
            "host" => "localhost",
            "name" => "",
            "user" => "",
            "password" => ""
        ),
        "medoo" => array(
            "master"=> array(
                "database_type"=> "mysql",
                "database_name" => "tobacco2",
                "server" => "localhost",
                "username" => 'root',
                'password' => '111111',

                // optional
                'port' => 3306,
                'charset' => 'utf8',
                // driver_option for connection, read more from http://www.php.net/manual/en/pdo.setattribute.php
                'option' => [
                    PDO::ATTR_CASE => PDO::CASE_NATURAL
                ]
            )
        ),
        "apple_apn" => array(
            "development_file" => "",
            "development_link" => "",
            "distribution_file" => "",
            "distribution_link" => ""
        )
    ),
    "log"=> array(
        "error"=> dirname(__FILE__)."/log/error.log",
        "info"=> dirname(__FILE__)."/log/info.log",
        "warning"=> dirname(__FILE__)."/log/warning.log"
    ),
    "android" => array(
        "key" => ""
    ),
    "olo" => array(
        "version" => "1.1"
    ) ,
    "views" => array(

    )
);

return $configs;