<?php
    $host ="localhost";
    $user = "root";
    $password = "";
    $dbname = "qcm_db";
    $db = new mysqli($host,$user,$password,$dbname);
    if ($db->connect_error) die("Erreur de connexion : " . $db->connect_error);

    if (isset($_SESSION['user_id'])) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $userId = $_SESSION['user_id'];
        $stmtCheck = $db->prepare("SELECT status FROM users WHERE id = ? LIMIT 1");
        $stmtCheck->bind_param("i", $userId);
        $stmtCheck->execute();
        $resultCheck = $stmtCheck->get_result();
        $userCheck = $resultCheck->fetch_assoc();
        $stmtCheck->close();
        if (!$userCheck || $userCheck['status'] === 'suspended') {
            $_SESSION = array();
            session_destroy();
            session_start();
            $_SESSION['alert'] = ['type' => 'error','message' => "Votre compte a été suspendu. Accès refusé."];
            header('Location: /auth/login.php');
            exit();
        }
    }












?>