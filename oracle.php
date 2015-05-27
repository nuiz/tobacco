<?php
/**
 * Created by PhpStorm.
 * User: NUIZ
 * Date: 19/5/2558
 * Time: 15:38
 */

$tns = "
(DESCRIPTION =
    (ADDRESS =
      (PROTOCOL=TCP)
      (HOST=192.168.0.24)
      (POST=1521)
    )
    (CONNECT_DATA =
      (SERVICE_NAME = TTM)
    )
  )
       ";
$db_username = "MISUSER";
$db_password = "misuser";
try{
//    $conn = new PDO("oci:dbname=".$tns,$db_username,$db_password);
    $conn = oci_connect($db_username, $db_password, $tns);
    var_dump($conn);
}catch(PDOException $e){
    echo ($e->getMessage());
}