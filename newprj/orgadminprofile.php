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
                                $sql = "INSERT INTO audit_trail(`action`, `user_id`) VALUES('Organization Admin updated prfile', :user_id)";
                                $result = $pdo->prepare($sql);
                                $result->bindParam(':user_id', $user["id"]);
                                $result->execute();
                            }

                            // Redirect to the same page
                            header("Location: orgadminhome.php");
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
    <link rel="icon" type="image/png" href="images/logos/newlogo.png">
    <title>CATS
    </title>
    <link rel="stylesheet" href="orgadminprofile.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">

</head>
    <body>
          <div class="header">
            <div class="left-section">
                <a href="orgadminhome.php" id="logo-link">
                    <img src="images/logos/newwhitetranspa.png" alt=""> 
                </a>
            </div>
            
            <div class="right-section">
                <a href="orgadminhome.php" class="dashh">Dashboard</a>
                <a href="orgadminorg.php" class="orgs">Organization</a>
                <a href="orgadminaudit.php" class="audtrail" >Audit Trail</a>
                <a href="orgeventlist.php" class="ev" >Event List</a> <!-- edited -->
                
               
                <div class="profile-link">
                  <a href="orgadminprofile.php" >
                    <img class="profile-picture" src="<?php echo $user["profile_picture"]; ?>" alt=""> <!-- <?php echo $profile_picture; ?> -->
                  </a>
                </div>
                <div class="user-name">
                  <a href="orgadminprofile.php" > <?php echo $user["username"]; ?></a> <!-- <?php echo $post['username']; ?> -->
              </div>
      
              <a class="logout" href="logout.php">Log out</a>
            </div>
          </div>
          <div class="profile-container">
            <h2> <?php echo $user['username']; ?>'s Profile</h2>
            <div class="profile-picture-container">
                <img class="profile-picture" src="<?php echo $user['profile_picture']; ?>" alt="">
            </div>
    
            <form action="orgadminprofile.php" method="post" enctype="multipart/form-data" autocomplete="off">
                <label for="profile_picture">Change Profile Picture:</label> <!-- THIS IS ADMIN ORG -->
                <input type="file" name="profile_picture" id="profile_picture" accept="image/*">
                <label for="new_username">Change Username:</label> <!-- THIS IS ADMIN ORG -->
                <input type="text" name="new_username" id="new_username" value="<?php echo $user['username']; ?>" required>
                <!--  value="<?php echo $user['username']; ?>"      --> <!-- THIS IS ADMIN ORG -->
                <label for="new_contact_number">Change Contact Number:</label>
                <input type="text" name="new_contact_number" id="new_contact_number" value="0<?php echo $user['contact']; ?>" pattern="09\d{9}" title="Please enter a valid 11-digit number starting with '09'" required>
                <!--   value="<?php echo $user['contact']; ?>"      --> <!-- THIS IS ADMIN ORG -->
                <label for="old_password">Old Password:</label>
                <input type="password" name="old_password" id="old_password" placeholder="Enter old password">
    <!-- THIS IS ADMIN ORG -->
                <label for="new_password">New Password:</label> <!-- THIS IS ADMIN ORG -->
                <input type="password" name="new_password" id="new_password" placeholder="Enter new password">
    <!-- THIS IS ADMIN ORG -->
                <label for="confirm_password">Confirm New Password:</label> <!-- THIS IS ADMIN ORG -->
                <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm new password">
    <!-- THIS IS ADMIN ORG -->
                <button class="save" type="submit" name="save">Save Changes</button>
            </form>
            <a href="orgadminhome.php">Back to Homepage</a>
        </div>

    </body>
</html>