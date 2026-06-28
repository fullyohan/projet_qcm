<?php 
session_start();
require_once '../security/auth-guard.php';
require_once '../../config/db.php'; 
require_auth();

$user_id  = (int)$_SESSION['user_id'];
$first_name = trim($_POST['first-name']);
$last_name = trim($_POST['last-name']);
$email    = trim($_POST['email']);
$password = $_POST['password'] ?? '';


if (empty($first_name) || empty($last_name) || empty($email)) {
    header("Location: /user/");
    exit();
}

$stmt_check = $db->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
$stmt_check->bind_param("si", $email, $user_id);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows > 0) {
    $_SESSION['alert'] = [
        'type' => 'error',
        'message' => "Cet email est déjà utilisé par un autre compte." 
    ];
    header("Location: /user/");
    exit();
}

$stmt_update = $db->prepare("UPDATE users SET first_name = ?,last_name = ?, email = ? WHERE id = ?");
$stmt_update->bind_param("sssi", $first_name,$last_name, $email, $user_id);
$stmt_update->execute();

$_SESSION['first_name'] = $first_name;
$_SESSION['last_name'] = $last_name;
$_SESSION['email'] = $email;

if (!empty($password)) {
    if (strlen($password) < 8) {
        $_SESSION['alert'] = [
            'type' => 'error',
            'message' => "Le nouveau mot de passe doit faire au moins 8 caractères." 
        ];
        header("Location: /user/");
        exit();
    }

    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt_password = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt_password->bind_param("si", $password_hash, $user_id);
    $stmt_password->execute();
}

$_SESSION['alert'] = [
    'type' => 'success',
    'message' => "Profil mis à jour avec succès !" 
];

header("Location: /user/");
exit();