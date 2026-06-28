<?php
    require_once "../../config/db.php";
    session_start();
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        $first_name = $_POST["first-name"];
        $last_name = $_POST["last-name"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0){
            $_SESSION['alert'] = [
                'type' => 'error',
                'message' => 'Cet email est déjà utilisé'
            ];
            header("Location: /auth/register.php");
            exit();
           
        } else {
            $password = password_hash($password,PASSWORD_DEFAULT);
            $sql="INSERT INTO users(first_name,last_name,email,password) VALUES(?,?,?,?)";
            $stmt=$db->prepare($sql);
            $stmt->bind_param("ssss",$first_name,$last_name, $email,$password);
            $stmt->execute();

            $user_id = $db->insert_id;
            $_SESSION['user_id'] = $user_id;
            $_SESSION['first_name'] = $first_name;
            $_SESSION['last_name'] = $last_name; 
            $_SESSION['email'] = $email;
            header("Location: /");
            exit();
        }
    }

?>