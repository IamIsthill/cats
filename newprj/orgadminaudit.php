<?php
session_start();
require_once './include/connect/dbcon.php';

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Sanitize input
    $user_id = $_SESSION["user_id"];

    // First query to get user information
    $getUserInfoQuery = "SELECT * FROM userlist WHERE id=:user_id";
    $resultUser = $pdo->prepare($getUserInfoQuery);
    $resultUser->bindParam(':user_id', $user_id);
    $resultUser->execute();
    $user = $resultUser->fetch();

    $auditTrailData = [];

  
    if ($user["access"] === "orgadmin") {
        $getOrgAuditQuery = "SELECT * FROM audit_trail 
                            INNER JOIN userlist ON audit_trail.user_id = userlist.id
                            WHERE userlist.organization=:organization 
                            ORDER BY audit_trail.timestamp DESC";
        $result_audit = $pdo->prepare($getOrgAuditQuery);
        $result_audit->bindParam(':organization', $user["organization"], PDO::PARAM_STR);
        $result_audit->execute();
        $auditTrailData = $result_audit->fetchAll(PDO::FETCH_ASSOC);
    } 
} catch (PDOException $exc) {
    $message = $exc->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="images/logos/newlogo.png">
    <title>Audit Trail
    </title>
    <link rel="stylesheet" href="orgadminaudit.css">
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
                    <img class="profile-picture" src="<?php echo $user["profile_picture"]; ?>" alt=""> <!--  -->
                  </a>
                </div>
                <div class="user-name">
                  <a href="orgadminprofile.php" target="_blank"> <?php echo $user["username"] ?> </a> <!-- <?php echo $post['username']; ?> -->
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
                <?php foreach ($auditTrailData as $record): ?>
                <tr>
                    <td><?php echo htmlspecialchars($record['aid'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo date('Y-m-d H:i:s', strtotime($record['timestamp'])); ?></td>
                    <td><?php echo htmlspecialchars($record['username'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($record['action'], ENT_QUOTES, 'UTF-8'); ?></td>
                </tr>
            <?php endforeach; ?>
                </tbody>
            </table></center>
        </div>
          
    </body>
</html>