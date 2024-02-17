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

try {
    // Fetch user details from the database
    $sql = "SELECT * FROM userlist WHERE id=:user_id";
    $result = $pdo->prepare($sql);
    $result->execute([":user_id" => $user_id]);
    $user = $result->fetch();
    $profile="images/profile/defaulticon.jpg";

    if (isset($_POST["save"])) {
        $password = $_POST["password"];
        $confirm_password = $_POST["confirm_password"];

        // Check if passwords match
        if ($password == $confirm_password) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO userlist(username, access, email, organization, contact, profile_picture, password)
                    VALUES(:username, 'orgadmin', :email, :organization, :contact, :profile_picture, :password)";
            $result = $pdo->prepare($sql);
            $result->bindParam(':username', $_POST["username"]);
            $result->bindParam(':email', $_POST["email"]);
            $result->bindParam(':organization', $_POST["organization"]);
            $result->bindParam(':contact', $_POST["contact"]);
            $result->bindParam(':profile_picture', $profile);  // Check if this is the desired behavior
            $result->bindParam(':password', $hashed_password);

            if ($result->execute()) {
                echo "<script>
                    alert('User added');
                    setTimeout(function() {
                        window.location.href = 'orgadminhome.php';
                    }, 1000); // Redirect after 3 seconds
                </script>";
            } else {
                echo "<script>
                    alert('Failed to add user');
                    setTimeout(function() {
                        window.location.href = 'orgadminhome.php';
                    }, 3000); // Redirect after 3 seconds
                </script>";
            }
        } else {
            echo "<script>
                alert('Passwords do not match');
                setTimeout(function() {
                    window.location.href = 'orgadduser.php';
                }, 3000); // Redirect after 3 seconds
            </script>";
        }
    }
} catch (PDOException $exc) {
    // Handle any database errors
    echo $exc->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="images/logos/newlogo.png">
    <title>CATS
    </title>
    <link rel="stylesheet" href="caddorg.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">

</head>
    <body>
          <div class="header">
            <div class="left-section">
                <a href="cadminhome.php" id="logo-link">
                    <img src="images/logos/newwhitetranspa.png" alt=""> 
                </a>
            </div>
            
            <div class="right-section">
                <a href="cadminhome.php" class="dashh">Dashboard</a>
                <a href="cadminorg.php" class="orgs">Organization</a>
                <a href="cadminaudit.php" class="audtrail" >Audit Trail</a>
                <a href="ceventlist.php" class="ev" >Event List</a> <!-- edited -->
                
               
                <div class="profile-link">
                  <a href="cadminprofile.php" target="_blank">
                    <img class="profile-picture" src="<?php echo $user["profile_picture"]; ?>" alt=""> <!-- <?php echo $profile_picture; ?> -->
                  </a>
                </div>
                <div class="user-name">
                  <a href="cadminprofile.php" target="_blank"> <?php echo $user["username"]; ?></a> <!-- <?php echo $post['username']; ?> -->
              </div>
      
              <a class="logout" href="logout.php">Log out</a>
            </div>
          </div>
          <div class="profile-container">
            <h2> Add Organization</h2>
            
    
            <form action="" method="post" enctype="multipart/form-data" autocomplete="off">
                <label for="Create_organization">Organization Name:</label>
                <input type="text" name="organization" id="Create_organization" placeholder="Enter organization here" required>
                <label for="Create_username">Username:</label>
                <input type="text" name="username" id="Create_username" placeholder="Enter username" required>
                <label for="Create_username">Email:</label>
                <input type="email" name="email" id="Create_email" placeholder="Enter email" required>
                <label for="create_contact_number">Contact Number:</label>
                <input type="text" name="contact" id="Create_contact_number" pattern="09\d{9}" title="Please enter a valid 11-digit number starting with '09'" placeholder="Enter contact number"required> 
                <label for="create_password">Password:</label>
                <input type="password" name="password" id="create_password" placeholder="Enter password">
    
                <label for="confirm_password">Password:</label>
                <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm password">
    
                <button class="save" type="submit" name="save">Add Organization</button>
            </form>
            <a href="cadminhome.php">Back to Homepage</a>
        </div>
          
    </body>
</html>