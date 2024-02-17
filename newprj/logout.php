<?php
session_start();

require_once './include/connect/dbcon.php';

if (isset($_SESSION["user_id"])) {
    $loggedInUser = $_SESSION["user_id"];

    $sql = "SELECT * FROM userlist WHERE id=:user_id";
    $result = $pdo->prepare($sql);
    $result->execute([':user_id' => $loggedInUser]);
    $access = $result->fetch();

    try {
        if ($access["access"] === "user") {
            $action = 'User logged out';
        } else if ($access["access"] === "superadmin") {
            $action = 'Super Admin logged out';
        } else if ($access["access"] === "churchadmin") {
            $action = 'Church Admin logged out';
        } else if ($access["access"] === "orgadmin") {
            $action = 'Organization Admin logged out';
        }

        $sql = "INSERT INTO `audit_trail`(`action`,`user_id`) VALUES (:action,:user_id)";
        $result = $pdo->prepare($sql);
        $result->execute([':action' => $action, ':user_id' => $access["id"]]);

    } catch (PDOException $e) {
        echo "Error inserting into audit trail: " . $e->getMessage();
    }

    unset($_SESSION['Username']);
    session_destroy();

    echo "<script>
            alert('You have logged out.');
            setTimeout(function() {
                window.location.href = 'index.php';
            }, 1000);
          </script>";
    exit();
} else {
    echo "User not logged in.";
}
?>
