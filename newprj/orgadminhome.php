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

      // Include the QR Code library
require_once './phpqrcode/qrlib.php';

function encodeImageToBase64($saveqr)
{
    $imageData = file_get_contents($saveqr);
    return base64_encode($saveqr);
}

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

$path = "images/qr/";
$saveqr = $path . time() . ".png";
$qrcode=time().".png";
$upload=$path . $qrcode;



    // Fetch user details from the database
    $sql = "SELECT * FROM userlist WHERE id=:user_id";
    $result = $pdo->prepare($sql);
    $result->execute([":user_id" => $user_id]);
    $user = $result->fetch();

 

    if(isset($_POST["post"])){
        $sql="INSERT INTO postlist(user_id,event, date, location, qr) VALUES(:user_id,:event, :date, :location, :qr)";
        $result=$pdo->prepare($sql);
        $result->bindParam(':user_id', $user["id"]);
        $result->bindParam(':event', $_POST["event"]);
        $result->bindParam(':date', $_POST["date"]);
        $result->bindParam(':location', $_POST["location"]);
        $result->bindParam(':qr', $upload);
        $result->execute();

        // Get the last inserted post ID
        $current_post_id = $pdo->lastInsertId();
        // Generate QR code and save the qr
QRcode::png("$current_post_id", $saveqr, "H", 10, 10);

        $sql="INSERT INTO attached_org(post_id, org) VALUES(:post_id, :org)";
        $result=$pdo->prepare($sql);
        $result->bindParam(':post_id', $current_post_id);
        $result->bindParam(':org', $user["organization"]);
        $result->execute();

        header('location: orgeventlist.php');
    }


     
    // Get the organization of the user
    $userOrganization = $user["organization"];
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
    <link rel="stylesheet" href="orgadminhome.css">
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
                  <a href="orgadminprofile.php" target="_blank">
                    <img class="profile-picture" src="<?php echo $user["profile_picture"]; ?>" alt=""> <!-- <?php echo $profile_picture; ?> -->
                  </a>
                </div>
                <div class="user-name">
                  <a href="orgadminprofile.php" target="_blank"> <?php echo $user["username"]; ?></a> <!-- <?php echo $post['username']; ?> -->
              </div>
      
              <a class="logout" href="logout.php">Log out</a>
            </div>
          </div>
          <?php
  // Fetch posts with attached organizations from the database
  $sql = "SELECT p.post_id, p.event, p.date, p.location, p.date_posted,u.profile_picture,u.church_member, u.username AS poster_name
  FROM postlist p
  INNER JOIN attached_org a ON p.post_id = a.post_id
  INNER JOIN userlist u ON p.user_id = u.id
  WHERE a.org = :organization AND p.date >= CURRENT_DATE() OR u.church_member != 'no' -- Modify to get future events
  ORDER BY p.date DESC;";

  $result = $pdo->prepare($sql);
  $result->bindParam(':organization', $user["organization"]);
  $result->execute();
  $posts = $result->fetchAll(PDO::FETCH_ASSOC);


?>
          <div class="churchname"> Archdiocese of San Fernando</div>
          <div class="post-container1">
            <h2>Create an event post</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data" autocomplete="off">
              <label for="postevent">What is the event?</label>
              <textarea name="event" id="postevent" placeholder="What?" required></textarea>
              <label for="birthdaytime">Date of the event:</label>
              <input type="date" id="birthdaytime" name="date">
              <label for="postname">Location of the event:</label>
              <textarea name="location" id="postname" placeholder="Where?" required></textarea>            
             
              <button type="submit" name="post">Post</button>
            </form>
            
          </div>
          <div class="present-event"> Recent Event Posts: </div>

  
            
                  <?php if (isset($posts) && !empty($posts)): ?>
        <?php $count = 0; ?>
        <?php foreach ($posts as $log): ?>
            <?php if ($count >= 3) break; ?>
            <div class="post-grid">
                <div class="post-container">
                    <div class="user-post-description">
                        <div class="poster-profile-picture">
                            <img class="poster-profile-picture" src="<?php echo $log['profile_picture']; ?>" alt="">
                        </div>
                        <div class="poster-info">
                            <div class="profile-name"><?php echo $log['poster_name']; ?></div>
                            <div class="date-posted"><?php echo $log['date_posted']; ?></div>
                        </div>
                        <div class="post-what">Event: <?php echo $log["event"]; ?> </div>
                        <div class="post-where">Date: <?php echo $log["date"]; ?> </div>
                        <div class="post-when">Location: <?php echo $log["location"]; ?></div>
                    </div>
                </div>
            </div>
            <?php $count++; ?>
        <?php endforeach; ?>
    <?php else: ?>
        <center><p>No events as of this moment.</p></center>
    <?php endif; ?>
          
    </body>
</html>



