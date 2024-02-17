<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION["user_id"];

require_once './include/connect/dbcon.php';

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sql = "SELECT * FROM userlist WHERE id=:user_id";
    $result = $pdo->prepare($sql);
    $result->execute([":user_id" => $user_id]);
    $user = $result->fetch();

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Check if file is uploaded without errors
        if (isset($_FILES["profile_picture"]) && $_FILES["profile_picture"]["error"] == 0) {
            $target_dir = "/Applications/XAMPP/xamppfiles/htdocs/newprj/images/profile/";

            // Check if passwords match
            $old_password = $_POST["old_password"];
            $new_password = $_POST["new_password"];
            $confirm_password = $_POST["confirm_password"];

            if (password_verify($old_password, $user["password"]) && $new_password == $confirm_password) {
                // Everything is okay, proceed with the update

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
                        $saved_picture = 'images/profile/' . $new_image_name;

                        try {
                            // Update user information
                            $new_username = htmlspecialchars($_POST["new_username"]);
                            $new_email = htmlspecialchars($_POST["new_email"]);
                            $new_contact = htmlspecialchars($_POST["new_contact"]);
                            $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);

                            $stmt = $pdo->prepare("UPDATE userlist SET username = :username, email = :email, password = :password, contact = :contact, profile_picture = :profile_picture WHERE id=:id");

                            $stmt->bindParam(':username', $new_username);
                            $stmt->bindParam(':email', $new_email);
                            $stmt->bindParam(':password', $new_password_hash);
                            $stmt->bindParam(':contact', $new_contact);
                            $stmt->bindParam(':profile_picture', $saved_picture);
                            $stmt->bindParam(':id', $user["id"]);
                            $stmt->execute();

                            $_SESSION["Username"] = $new_username;

                            if ($stmt) {
                                $sql = "INSERT INTO audit_trail(`action`, `user_id`) VALUES('User updated', :user_id)";
                                $result = $pdo->prepare($sql);
                                $result->bindParam(':user_id', $user["id"]);
                                $result->execute();
                            }

                            // Redirect to the same page
                            header("Location: home.php");
                            exit();
                        } catch (PDOException $e) {
                            echo "Error: " . $e->getMessage();
                        }
                    }
                }
            } else {
                echo "Invalid old password or new passwords do not match.";
            }
        } else {
            echo "Invalid file or no file uploaded.";
        }
    }
} catch (PDOException $exc) {
    $message = $exc->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Profile</title>
    <link rel="stylesheet" href="userprofilestyle.css">
    <link rel="shortcut icon" href="images/logo/Logo.png" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
</head>
<body>

<div class="header">
            <div class="left-section">
                <a href="userhomepage.html" id="logo-link">
                    <img src="images/logos/newwhitetranspa.png" alt=""> 
                </a>
            </div>
            
            <div class="right-section">
                <a href="home.php" class="dashh">Dashboard</a>
                <a href="userorg.php" class="orgs">Organization</a>
                <a href="audit.php" class="audtrail" >Audit Trail</a>
                <a href="userattendance.php" class="ah" >Attendance History</a> <!-- edited -->
                <a href="scanqr.php" class="scanqr">Scan QR</a>
              <div class="profile-link">
                <a href="userprofile.html" target="_blank">
                  <img class="profile-picture" src=" <?php echo $user["profile_picture"]; ?>" alt=""> <!-- <?php echo $profile_picture; ?> -->
                </a>
              </div>
              <div class="user-name">
                <a href="userprofile.php" target="_blank"><?php echo $user["username"]; ?></a> <!-- <?php echo $post['username']; ?> -->
            </div>
            
             
              <a class="logout" href="logout.php">Log out</a>
            </div>
          </div>

<div class="profile-container">
    <h2> <?php echo htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8'); ?>'s Profile</h2>
    <div class="profile-picture-container">
        <img class="profile-picture" src="<?php echo htmlspecialchars($user['profile_picture'], ENT_QUOTES, 'UTF-8'); ?>" alt="">
    </div>

    <form action="userprofile.php?id=<?php echo $user["id"]; ?>" method="POST" enctype="multipart/form-data" autocomplete="off">
        <label for="profile_picture">Change Profile Picture:</label>
        <input type="file" name="profile_picture" id="profile_picture" accept=".jpg,.png,.jpeg">
        <label for="new_username">Change Username:</label>
        <input type="text" name="new_username" id="new_username" value="<?php echo htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8'); ?>" required>
        <label for="new_email">Change Email:</label>
        <input type="text" name="new_email" id="new_email" value="<?php echo htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8'); ?>" required>
        <label for="new_contact_number">Change Contact Number:</label>
        <input type="text" name="new_contact" id="new_contact_number" value="0<?php echo htmlspecialchars($user['contact'], ENT_QUOTES, 'UTF-8'); ?>" pattern="09\d{9}" title="Please enter a valid 11-digit number starting with '09'" required>
        <label for="old_password">Old Password:</label>
        <input type="password" name="old_password" id="old_password" placeholder="Enter old password">
        <label for="new_password">New Password:</label>
        <input type="password" name="new_password" id="new_password" placeholder="Enter new password">
        <label for="confirm_password">Confirm New Password:</label>
        <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm new password">
        <button class="save" type="submit" name="save">Save Changes</button>
    </form>

    <a href="home.php">Back to Homepage</a>
</div>

</body>
</html>
