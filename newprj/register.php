<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once './include/connect/dbcon.php';

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = null; // Define $stmt and set it to null

    if (isset($_POST["register"])) {
        $username = $_POST["regUsername"];
        $password = password_hash($_POST["regPassword"], PASSWORD_DEFAULT);
        $email = $_POST["regEmail"];
        $access = "user";
        $church_member = 1;

        $target_dir = "/Applications/XAMPP/xamppfiles/htdocs/newprj/images/profile/";

        // Check if an image was uploaded
        if ($_FILES["profile_picture"]["error"] === 4) {
            echo "<script>alert('Image does not exist')</script>";
        } else {
            $file_name = $_FILES["profile_picture"]["name"];
            $file_size = $_FILES["profile_picture"]["size"];
            $tmp_name = $_FILES["profile_picture"]["tmp_name"];

            $valid_image_extension = ['jpg', 'png', 'jpeg'];
            $image_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            if (!in_array($image_extension, $valid_image_extension)) {
                echo "<script>alert('Invalid image extension.')</script>";
            } else if ($file_size > 1000000000) {
                echo "<script>alert('File is too large')</script>";
            } else {
                $new_image_name = uniqid() . '.' . $image_extension;
                $destination = $target_dir . $new_image_name;
                move_uploaded_file($tmp_name, $destination);
                $saved_picture= 'images/profile/' . $new_image_name;

                $sql = "INSERT INTO userlist (username, email, password, access, profile_picture, church_member) VALUES (:username, :email, :password, :access, :profile_picture, :church_member)";
                $stmt = $pdo->prepare($sql);

                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':password', $password);
                $stmt->bindParam(':access', $access);
                $stmt->bindParam(':profile_picture', $saved_picture);
                $stmt->bindParam(':church_member', $church_member);

                $stmt->execute();
            }
        }
    }

    if ($stmt) {
        header("Location: index.php");
        exit();
    }
} catch (PDOException $e) {
    $message = $e->getMessage();
    echo $message; // You might want to handle errors more gracefully
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CAMS Registration</title>
    <link rel="stylesheet" type="text/css" href="./include/style/styles.css">
</head>

<body>
    <br />
    <div style="width:500px;">
        <?php
        if (isset($message)) {
            echo '<label>' . $message . '</label>';
        }
        ?>
        <h3 align="">CAMS Registration</h3><br />
        <form method="POST" action="" enctype="multipart/form-data" autocomplete="off">
            Username <input type="text" name="regUsername" required><br />
            Email <input type="email" name="regEmail" required><br />
            Password <input type="password" name="regPassword" required><br />
            <label for="profile_picture">Change Profile Picture:</label>
            <input type="file" name="profile_picture" id="profile_picture" accept=".jpg,.png,.jpeg"><br />
            <input type="submit" name="register" value="Register">
        </form>
    </div>
    <br />
</body>

</html>
