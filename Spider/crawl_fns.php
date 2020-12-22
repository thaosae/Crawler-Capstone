<?php
//ini_set("display_errors","On");
//error_reporting(E_ALL);
require ('db_fns.php');


function checkURLExists($crawlInput, $crawlURL){
    $conn = new mysqli("localhost", "root", "", "crawler");
        $query = "select * from $crawlURL
             where crawledurl='".$crawlInput."'";
        $result = $conn->query($query);
        if ((!$result) || ($result->num_rows != 0)) {
            return true;
        }
         else {
             return false;
         }
}

function checkWordEngExists($queriedResult){
    //Maximum execution time of 30 sec error fix for line 22 to fix
    // go to php.ini and change the maximum execution time allow
    $conn = new mysqli("localhost", "root", "", "crawler");
    $query = "select * from english
             where word='".$queriedResult."'";
    $result = $conn->query($query);
    if ((!$result) || ($result->num_rows != 0)) {
        return true;
    }
    else {
        return false;
    }
}

function checkWordTelExists($queriedResult){
    $conn = new mysqli("localhost", "root", "", "crawler");
    $query = "select * from telugu
             where word='".$queriedResult."'";
    $result = $conn->query($query);
    if ((!$result) || ($result->num_rows != 0)) {
        return true;
    }
    else {
        return false;
    }
}

function insertURLToDB($crawlInput, $sunset, $crawlURL){
// inserts a new crawled url into the database
    $conn = new mysqli("localhost", "root", "", "crawler");
    $insert_date = date("m/d/Y");
    // check crawled url does not already exist
    // checkExists($crawlInput);
    // insert new crawled url
    $query = "insert into $crawlURL values(null, '".$crawlInput."', '".$insert_date."')";

    $result = $conn->query($query);
    if (!$result) {
        return false;
    } else {
        //fix error for 63 array and boolean
        //$result = array($result);
        // return db2_fetch_array($result);
        return array_values(array($result));
    }
}

function insertEngTXTToDB($textFound){
// inserts a new crawled url into the database
    $conn = new mysqli("localhost", "root", "", "crawler");
    // check crawled url does not already exist
    // checkExists($crawlInput);
    // insert new crawled url
    $query = "insert into english(word, char_len) values('".$textFound."','".strlen($textFound)."')"; //give length to words that are found
    $result = $conn->query($query);
    if (!$result) {
        return false;
    } else {
        //$result = array($result);
        // return db2_fetch_array($result);
        set_time_limit(0);
        return array_values(array($result));
    }
}

function insertTelTXTToDB($input){
// inserts a new crawled url into the database
    $conn = new mysqli("localhost", "root", "", "crawler");
    // check crawled url does not already exist
    // checkExists($crawlInput);
    // insert new crawled url
    $query = "insert into telugu values(null, '".$input."')";
    $result = $conn->query($query);
    if (!$result) {
        return false;
    } else {
        //$result = array($result);
        // return db2_fetch_array($result);
        set_time_limit(0);
        return array_values(array($result));
    }
}

function sanitizeInput($data) {
    $data = trim($data); //delete spaces before and after
    $data = stripslashes($data); //get rid of slashes
    $data = htmlspecialchars($data); //get rid of html special characters
    $data = html_entity_decode($data); //get rid of html spaces and quotes etc
    $data = preg_replace('/[^A-Za-z]/', ' ', $data); //leave alpha and space only
    $data = preg_replace('/ +/', ' ', $data);

    return $data;
}

function sanitizeTelInput($data) {
    $data = trim($data); //delete spaces before and after
    $data = stripslashes($data); //get rid of slashes
    $data = html_entity_decode($data); //get rid of html spaces and quotes etc
    $data = preg_replace('/[A-Za-z0-9]/', ' ', $data); //leave alpha and space only
    $data = preg_replace('/ +/', ' ', $data);


    return $data;
}

function inTelRange($word){
  if(preg_match('/[\x{0C00}-\x{0C7F}]/u', $word) > 0) {
      return true;
  }
    else {
        return false;
    }
}
