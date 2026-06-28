<?php
session_start();
require_once "../../config/db.php";
require_once "../security/auth-guard.php";
require_auth();

$user_id = (int)($_SESSION['user_id'] ?? 0);
$action = $_POST['action'] ?? '';
$target_user_id = (int)($_POST['user_id'] ?? 0);

if ($user_id <= 0 || !$action || $target_user_id <= 0) {
    header("Location: /admin/user");
    exit();
}

$sql_check = "SELECT status FROM users WHERE id = ?";
$stmt_check = $db->prepare($sql_check);
$stmt_check->bind_param("i", $user_id);
$stmt_check->execute();
$result_check = $stmt_check->get_result();
$current_user = $result_check->fetch_assoc();
$stmt_check->close();

if (!$current_user || $current_user['status'] !== 'admin') {
    $_SESSION['error'] = "Action non autorisée. Vous devez être administrateur.";
    header("Location: /user/dashboard");
    exit();
}
if ($target_user_id === $user_id && in_array($action, ['demote', 'suspend', 'delete_user'])) {
    $_SESSION['error'] = "Vous ne pouvez pas modifier ou supprimer votre propre compte.";
    header("Location: /admin/");
    exit();
}

switch ($action) {
    case 'promote':
        $sql = "UPDATE users SET status = 'admin' WHERE id = ?";
        executeModeration($db, $sql, $target_user_id, "L'utilisateur a été promu administrateur.");
        break;

    case 'demote':
        $sql = "UPDATE users SET status = 'user' WHERE id = ?";
        executeModeration($db, $sql, $target_user_id, "L'administrateur a été rétrogradé au rang d'utilisateur.");
        break;

    case 'suspend':
        $sql = "UPDATE users SET status = 'suspended' WHERE id = ?";
        executeModeration($db, $sql, $target_user_id, "Le compte de l'utilisateur a été suspendu.");
        break;

    case 'unsuspend':
        $sql = "UPDATE users SET status = 'user' WHERE id = ?";
        executeModeration($db, $sql, $target_user_id, "Le compte de l'utilisateur a été réactivé.");
        break;

    case 'delete_user':
        $sql = "DELETE FROM users WHERE id = ?";
        executeModeration($db, $sql, $target_user_id, "L'utilisateur a été définitivement supprimé.");
        break;

    default:
        header("Location: /admin/");
        exit();
}

function executeModeration($db, $sql, $target_id, $success_message) {
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $target_id);
    if ($stmt->execute()) {
        $_SESSION['alert'] = ['type' => 'success' ,'message' => $success_message];
    } else {
        $_SESSION['alert'] = ['type' => 'error' ,'message' => "Une erreur est survenue lors de l'opération."] ;
    }
    $stmt->close();
    header("Location: /admin/");
    exit();
}