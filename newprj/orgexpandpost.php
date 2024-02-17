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

    $sql = "SELECT * FROM postlist INNER JOIN userlist on userlist.id=postlist.user_id WHERE post_id=:post";
    $result = $pdo->prepare($sql);
    $result->execute([":post" => $post_id]);
    $post = $result->fetch();

    
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
    <link rel="stylesheet" href="orgexpandpost.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">

</head>
    <body >
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
                  <a href="orgadminprofile.php" target="_blank">
                    <img class="profile-picture" src="<?php echo $user["profile_picture"]; ?>" alt=""> <!-- <?php echo $profile_picture; ?> -->
                  </a>
                </div>
                <div class="user-name">
                  <a href="orgadminprofile.php" target="_blank"><?php echo $user["username"]; ?></a> <!-- <?php echo $post['username']; ?> -->
              </div>
      
              <a class="logout" href="logout.php">Log out</a>
            </div>
          </div>
          
          <div class="post-container">
               
            <div>
                <div>
                  <div class="user-profile">
                    <div class="poster-profile-picture">
                        <img class="poster-profile-picture" src="<?php echo $post['profile_picture']; ?>" alt="">
                     
                    </div>
                    <div class="poster-info">
                      <div class="profile-name"><?php echo $post['username']; ?></div>
                      <div class="date-posted"><?php echo $post['date_posted']; ?></div>
                    </div>
                  </div>
                </div>
          <div class="user-post-description">
            <div class="post-what">  Event:        <?php echo $post['event']; ?></div>
            <div class="post-where"> Date:          <?php echo $post['date']; ?></div>
            <div class="post-when">  Location:      <?php echo $post['location']; ?></div>
            <div class="qr-image"><img src="<?php echo $post['qr']; ?>">  </div> 
              
            <div class="mga-buttons">
                <a href="orgeditpost.php?id=<?php echo $post['post_id'];?>">Edit  </a>
              
              </div>
              <div class ="delete-button">
                        <a href="orgdeletepost.php?id=<?php echo $log['post_id'];?>" >Delete</a>
              </div>
             
               
            
          </div> 
        </div>
    
      </div>
          
    </body>
</html>