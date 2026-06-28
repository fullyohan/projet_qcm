<?php 
    require_once "../../config/db.php";
    require_once "../security/auth-guard.php";
    session_start();
    require_guest();

    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        $email = $_POST["email"];
        $password = $_POST["password"];
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1){
            $user = $result->fetch_assoc();
            if (password_verify($password,$user["password"])) {
                $_SESSION["user_id"] = $user["id"];
                $_SESSION['first_name'] = $user['first_name'];
                $_SESSION['last_name'] = $user['last_name']; 
                $_SESSION['email'] = $user['email'];

                if ($user['status'] === 'suspended') {
                    $_SESSION['alert'] = ['type' => 'error','message' => "Votre compte a été suspendu."];
                    header('Location: /auth/login.php');
                    exit();
                }

                header("Location: /");
                exit();
            } else {
                $_SESSION['alert'] = [
                    'type' => 'error',
                    'message' => 'Mot de passe incorrect.'
                ];
                header("Location: /auth/login.php");
                exit();
            }
        } else {
            $_SESSION['alert'] = [
                'type' => 'error',
                'message' => 'Aucun compte trouvé avec cette adresse e-mail.'
            ];
            header("Location: /auth/login.php");
            exit();
        }
    }
