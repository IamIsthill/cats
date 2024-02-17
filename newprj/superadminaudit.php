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

    $sql = "SELECT * FROM userlist";
    $result = $pdo->prepare($sql);
    $userlist = $result->fetchAll(PDO::ASSOC);

    if ($user["access"] === "superadmin") {
        $getSuperAdminAuditQuery = "SELECT * FROM audit_trail 
                                    INNER JOIN userlist ON audit_trail.user_id = userlist.id
                                    ORDER BY audit_trail.timestamp DESC";
        $result_audit = $pdo->prepare($getSuperAdminAuditQuery);
        $result_audit->execute();
        $audit = $result_audit->fetchAll(PDO::FETCH_ASSOC);
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
    <link rel="stylesheet" href="superadminhome.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">

</head>
    <body>
          <div class="header">
            <div class="left-section">
                <a href="superadminhomepage.php" id="logo-link">
                    <img src="images/logos/newwhitetranspa.png" alt=""> 
                </a>
            </div>
            
            <div class="right-section">
                <a href="superadminhome.php" class="dashh">Dashboard</a>
                <a href="superadminacc.php" class="orgs">Accounts</a>
                <a href="superadminaudit.php" class="audtrail" >Audit Trail</a>
                
                
               
                <div class="profile-link">
                  <a href="superadminprofile.php" >
                    <img class="profile-picture" src="<?php echo $user['profile_picture']; ?>" alt=""> <!-- <?php echo $profile_picture; ?> -->
                  </a>
                </div>
                <div class="user-name">
                  <a href="superadminprofile.php" > <?php echo $user['username']; ?></a> <!-- <?php echo $post['username']; ?> -->
              </div>
      
              <a class="logout" href="logout.php">Log out</a>
            </div>
          </div>
          
          <div class="audit-layout">
            <center><h2>Audit Trail</h2></center>
           <center> <table class="table">
                <thead class="thead-primary">
                    <tr>
                        <th>#</th>
                        <th>Date and Time</th>
                        <th>Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($audit as $record): ?>  
                        <tr>
                            <td> <?php echo $record['aid']; ?> </td>
                            <td>   <?php echo $record['timestamp']; ?>       </td>
                            <td>   <?php echo $record['username']; ?>       </td>
                            <td>  <?php echo $record['action']; ?>       </td>
                        </tr>
                   <?php endforeach; ?> 
                </tbody>
            </table></center>
        </div>
          
    </body>
</html>