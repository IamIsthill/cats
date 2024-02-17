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

// Initialize post_id
$post_id = null;

// Check if post_id is provided in the URL
if (isset($_GET["id"])) {
    $post_id = $_GET["id"];
}

try {
    // Fetch user details from the database
    $sql = "SELECT * FROM userlist WHERE id=:user_id";
    $result = $pdo->prepare($sql);
    $result->execute([":user_id" => $user_id]);
    $user = $result->fetch();

     // Check if post_id is valid
     if (!$post_id) {
      // Redirect or handle the case when post_id is not provided
      header("Location: orgadminhome.php");
      exit();
  }

    // Fetch post details from the database
    $sql = "SELECT * FROM postlist WHERE post_id=:post_id";
    $result = $pdo->prepare($sql);
    $result->execute([":post_id" => $post_id]);
    $post = $result->fetch();

    if (isset($_POST["post"])) {
        // Update post in the database
        $updateSql = "UPDATE postlist SET event = :event, date = :date, location = :location WHERE post_id=:id";
        $updateResult = $pdo->prepare($updateSql);
        $updateResult->bindParam(':event', $_POST['event']);
        $updateResult->bindParam(':date', $_POST['date']);
        $updateResult->bindParam(':location', $_POST['location']);
        $updateResult->bindParam(':id', $post_id);
        $updateSuccess = $updateResult->execute();

        if ($updateSuccess) {
            // Log the action in the audit trail
            $auditSql = "INSERT INTO audit_trail(`action`, `user_id`) VALUES('Church admin updated post', :user_id)";
            $auditResult = $pdo->prepare($auditSql);
            $auditResult->bindParam(':user_id', $user["id"]);
            $auditResult->execute();
    }
    header('location: orgeventlist.php');
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
    <link rel="stylesheet" href="cadminedit.css">
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
          <<div class="post-container1">
            <h2>Edit Post</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . '?id=' . $post_id); ?>" method="post" enctype="multipart/form-data" autocomplete="off">
              <label for="postevent">What is the event?</label>
              <textarea name="event" id="postevent"  required><?php echo $post["event"]; ?></textarea>
              <label for="birthdaytime">Date of the event:</label>
              <input type="date" id="birthdaytime" name="date" value="<?php echo $post["date"]; ?>" >
              <label for="postname">Location of the event:</label>
              <textarea name="location" id="postname" required><?php echo $post["location"]; ?></textarea>            
               <div class="qr-image"><img src="<?php echo $post["qr"]; ?>">  </div> 
              <button type="submit" name="post">Save</button>
            </form>
            
          </div>

          
    </body>
</html>