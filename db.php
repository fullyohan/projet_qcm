<?php
$host ="localhost";
    $user = "root";
    $password = "";
    $bdname = "projet_qcm";
    $conn = new mysqli($host,$user,$password,$bdname);
    if ($conn->connect_error) {

    die("Erreur de connexion : " . $conn->connect_error);

    }?>