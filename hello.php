<?php
/**
 * Created by PhpStorm.
 * User: NUIZ
 * Date: 20/5/2558
 * Time: 11:05
 */

if($_SERVER["REQUEST_METHOD"]=="GET"){
    echo "Hello World!";
}
else if($_SERVER["REQUEST_METHOD"]=="POST"){
    $fileName = "test.json";
    $jsonText = file_get_contents($fileName);
    $jsonObj = json_decode($jsonText, true);

    $jsonObj["data"][] = $_POST;
    file_put_contents($fileName, json_encode($jsonObj));

    header("Content-type: application/json");
    echo json_encode($_POST);
}