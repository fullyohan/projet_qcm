<?php
    function require_auth(){
        if (!isset($_SESSION["user_id"])) {

        header("Location: login.php");

        exit();

    }

    function require_guest(){
        if (isset($_SESSION["user_id"])) {

        header("Location: qcm.php");

        exit();

    }

}
    
?>