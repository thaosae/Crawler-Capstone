<?php
/**
 * Created by PhpStorm.
 * User: June
 * Date: 10/11/2016
 * Time: 6:40 PM
 */


function db_connect() {
    //$result = new mysqli('localhost', 'book_sc', 'password', 'book_sc');
   $result = new mysqli("localhost", "root", "", "crawler");
 //$result = new mysqli("127.0.0.1", "root", "Curry", "webcrawldb", "3306");
    if (!$result) {
        return false;
    }
    $result->autocommit(TRUE);
    return $result;
}
    
function db_result_to_array($result) {
   $res_array = array();

    for ($count=0; $row = $result->fetch_assoc(); $count++) {
        $res_array[$count] = $row;
   }

    return $res_array;
}

?>
