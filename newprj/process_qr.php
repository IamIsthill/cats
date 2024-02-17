<?php
session_start();

require_once './include/connect/dbcon.php';

if (isset($_GET['result'])) {
    $result = $_GET['result'];

    // Assuming your QR code result is a post ID
    $sql = "SELECT * FROM postlist WHERE post_id = :post";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':post', $result);
    $stmt->execute();
    
    if ($post = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // If post is found, update attendance
        $sql = "INSERT INTO attendance (post_id, user_id, remarks) VALUES (:post_id, :user_id, :remarks)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':post_id', $post['post_id']);
        $stmt->bindParam(':user_id', $_SESSION['user_id']);
        $stmt->bindValue(':remarks', "Attended");
        $stmt->execute();

        

    } else {
        echo 'Invalid QR code or post not found!';
    }
} else {
    echo 'Invalid request!';
}
?>
