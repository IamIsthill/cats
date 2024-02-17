<?php
require_once './include/connect/dbcon.php';

if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    // Perform the delete operation
    $sql = "DELETE FROM userlist WHERE id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);

    if ($stmt->execute()) {
        // Redirect back to the account list page after successful deletion
        header("Location: superadminacc.php");
        exit();
    } else {
        echo "Error deleting user.";
    }
} else {
    echo "Invalid request.";
}
?>
