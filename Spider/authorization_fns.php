<?php
/**
 * Created by PhpStorm.
 * User: June
 * Date: 10/11/2016
 * Time: 7:51 PM
 */
//require_once('db_fns.php');

function login($username, $password) {
// check username and password with db
// if yes, return true
// else return false

    // connect to db
    $conn = db_connect();
    if (!$conn) {
        return 0;
    }

    // check if username is unique
    $result = $conn->query("select * from admin
                         where username='".$username."'
                         and password = sha1('".$password."')");
    if (!$result) {
        return 0;
    }

    if ($result->num_rows>0) {
        return 1;
    } else {
        return 0;
    }
}

function check_admin_user() {
// see if somebody is logged in and notify them if not

    if (isset($_SESSION['admin_user'])) {
        return true;
    } else {
        return false;
    }
}

?>
