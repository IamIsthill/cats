<?php
session_start();

// Redirect to index.php if the user is not logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit();
}

// Include the database connection file
require_once './include/connect/dbcon.php';

// Get the username from the session
$user_id = $_SESSION["user_id"];
if (isset($_GET["id"])) {
    $post_id = $_GET["id"];
    // Now use $post_id in your SQL queries.
}


try {
    // Fetch user details from the database
    $sql = "SELECT * FROM userlist WHERE id=:user_id";
    $result = $pdo->prepare($sql);
    $result->execute([":user_id" => $user_id]);
    $user = $result->fetch();

    $sql = "DELETE FROM postlist where post_id = :post_id";
    $result = $pdo->prepare($sql);
    $result->execute([":post_id" => $post_id]);
    if ($result) {
        // Log the action in the audit trail
        $auditSql = "INSERT INTO audit_trail(`action`, `user_id`) VALUES('Church admin deleted post', :user_id)";
        $auditResult = $pdo->prepare($auditSql);
        $auditResult->bindParam(':user_id', $user["id"]);
        $auditResult->execute();
}
    header('Location: ceventlist.php');

}catch (PDOException $exc) {
        $message = $exc->getMessage();
    }
    ?>
