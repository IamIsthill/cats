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
    <title>CAMS</title>
    <link rel="stylesheet" href="userstyle.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
</head>

<body>
<div class="header">
            <div class="left-section">
                <a href="home.php" id="logo-link">
                    <img src="images/logos/newwhitetranspa.png" alt=""> 
                </a>
            </div>
            
            <div class="right-section">
                <a href="home.php" class="dashh">Dashboard</a>
                <a href="userorg.php" class="orgs">Organization</a>
                <a href="audit_trail.php" class="audtrail" >Audit Trail</a>
                <a href="userattendance.php" class="ah" >Attendance History</a> <!-- edited -->
                <a href="scanqr.php" class="scanqr">Scan QR</a>
              <div class="profile-link">
                <a href="userprofile.php" target="_blank">
                  <img class="profile-picture" src=" <?php echo $user["profile_picture"]; ?>" alt=""> <!-- <?php echo $profile_picture; ?> -->
                </a>
              </div>
              <div class="user-name">
                <a href="userprofile.php" target="_blank"><?php echo $user["username"]; ?></a> <!-- <?php echo $post['username']; ?> -->
            </div>
            
             
              <a class="logout" href="logout.php">Log out</a>
            </div>
          </div>

    <div class="churchname"> Archdiocese of San Fernando</div>
    <div class="churchimg">
        <img src="images/Cathedral.png" alt="">
    </div>

    <?php
    // Fetch posts with attached organizations from the database
    $sql = "SELECT p.post_id, p.event, p.date, p.location, p.date_posted, u.username AS poster_name
    FROM postlist p
    INNER JOIN attached_org a ON p.post_id = a.post_id
    INNER JOIN userlist u ON p.user_id = u.id
    WHERE a.org = :organization AND p.date >= CURRENT_DATE()  -- Modify to get future events
    ORDER BY p.date DESC;";

    $result = $pdo->prepare($sql);
    $result->bindParam(':organization', $userOrganization);
    $result->execute();
    $posts = $result->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <div class="text-title">Announcements</div>

    <div class="present-event">Present Events:</div>

    <?php if (isset($posts) && !empty($posts)): ?>
        <?php $count = 0; ?>
        <?php foreach ($posts as $log): ?>
            <?php if ($count >= 3) break; ?>
            <div class="post-grid">
                <div class="post-container">
                    <div class="user-post-description">
                        <div class="poster-profile-picture">
                            <img class="poster-profile-picture" src="<?php echo $user["profile_picture"]; ?>" alt="">
                        </div>
                        <div class="poster-info">
                            <div class="profile-name"><?php echo $log['poster_name']; ?></div>
                            <div class="date-posted"><?php echo $log['date_posted']; ?></div>
                        </div>
                        <div class="post-what">Event: <?php echo $log["event"]; ?> </div>
                        <div class="post-where">Date: <?php echo $log["date"]; ?> </div>
                        <div class="post-when">Location: <?php echo $log["location"]; ?></div>
                        <div class="buttons">
                            <a href="attendqr.html" target="attendance.php">
                                <button class="attend-button" data-post-id="<?php echo $log['post_id']; ?>">Attend</button>
                            </a>
                        </div>
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