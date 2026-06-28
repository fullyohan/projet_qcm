<?php
session_start();
session_destroy();
$_SESSION['alert'] = [
    'type' => 'success',
    'message' => 'Vous vous etes deconecte'
];
header("location: /");
exit();