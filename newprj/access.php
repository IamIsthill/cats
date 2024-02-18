<?php
session_start();

require_once './include/connect/dbcon.php';

if (isset($_POST["login"])) {
    $username = $_POST["Username"];
    $password = $_POST["Password"];

    $sql = "SELECT * FROM userlist WHERE username=:username";
    $result = $pdo->prepare($sql);
    $result->execute([':username' => $username]);
    $user = $result->fetch();

    if ($user && password_verify($password, $user["password"])) {
        $_SESSION["user_id"] = $user["id"];

        
        if($user["access"]=="user"){
        $action = 'User logged in';
        }
        if($user["access"]=="superadmin"){
            $action = 'Super Admin logged in';
        }
        if($user["access"]=="churchadmin"){
            $action = 'Church Admin logged in';
        }
        if($user["access"]=="orgadmin"){
            $action = 'Organization Admin logged in';
        }
        $sql = "INSERT INTO audit_trail (action, user_id) VALUES (:action, :user_id)";
        $result = $pdo->prepare($sql);
        $result->execute([':action' => $action, ':user_id' => $user["id"]]);
        if($user["access"]=="user"){
            header("Location: home.php");
            }
            if($user["access"]=="superadmin"){
                header("Location: superadminhome.php");
            }
            if($user["access"]=="churchadmin"){
                header("Location: cadminhome.php");
            }
            if($user["access"]=="orgadmin"){
                header("Location: orgadminhome.php");
            }
        
        exit();
    } else {
        $message = "<label>Wrong Data</label>  ";
       
    }
} elseif (isset($_POST["register"])) {
    header("Location: register.php");
    exit();
}
?>
