<?php

function require_auth() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: /auth/login");
        exit();
    }
}

function require_guest() {
    if (isset($_SESSION['user_id'])) {
        $_SESSION['alert'] = ['type' => 'error','message' => "Vous etes deja connecte"] ;
        header("Location: /user/dashboard");
        exit();
    }
}

function require_admin($db) {
    $userId = $_SESSION['user_id'];
    $stmt = $db->prepare("SELECT status FROM users WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    if (!$user || $user['status'] !== 'admin') {
        $_SESSION['alert'] = ['type' => 'error' ,"message" =>"Accès refusé. Zone réservée aux administrateurs."];
        header('Location: /user/dashboard.php');
        exit();
    }
}