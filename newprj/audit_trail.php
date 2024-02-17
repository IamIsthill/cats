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

    if ($user["access"] === "user") {
        $getUserAuditQuery = "SELECT * FROM audit_trail 
                            INNER JOIN userlist ON audit_trail.user_id = userlist.id
                            WHERE userlist.username=:username 
                            ORDER BY audit_trail.timestamp DESC";
        $result_audit = $pdo->prepare($getUserAuditQuery);
        $result_audit->bindParam(':username', $user["username"], PDO::PARAM_STR);
        $result_audit->execute();
        $auditTrailData = $result_audit->fetchAll(PDO::FETCH_ASSOC);
    } elseif ($user["access"] === "orgadmin") {
        $getOrgAuditQuery = "SELECT * FROM audit_trail 
                            INNER JOIN userlist ON audit_trail.user_id = userlist.id
                            WHERE userlist.organization=:organization 
                            ORDER BY audit_trail.timestamp DESC";
        $result_audit = $pdo->prepare($getOrgAuditQuery);
        $result_audit->bindParam(':organization', $user["organization"], PDO::PARAM_STR);
        $result_audit->execute();
        $auditTrailData = $result_audit->fetchAll(PDO::FETCH_ASSOC);
    } elseif ($user["access"] === "churchadmin") {
        $getChurchAuditQuery = "SELECT * FROM audit_trail 
                            INNER JOIN userlist ON audit_trail.user_id = userlist.id
                            WHERE userlist.access=:access 
                            ORDER BY audit_trail.timestamp DESC";
        $result_audit = $pdo->prepare($getChurchAuditQuery);
        $result_audit->bindParam(':access', "churchadmin", PDO::PARAM_STR);
        $result_audit->execute();
        $auditTrailData = $result_audit->fetchAll(PDO::FETCH_ASSOC);
    } elseif ($user["access"] === "superadmin") {
        $getSuperAdminAuditQuery = "SELECT * FROM audit_trail 
                                    INNER JOIN userlist ON audit_trail.user_id = userlist.id
                                    ORDER BY audit_trail.timestamp DESC";
        $result_audit = $pdo->prepare($getSuperAdminAuditQuery);
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audit Trail</title>
    <link rel="stylesheet" href="useraudit.css">
    <link rel="shortcut icon" href="images/logo/Logo.png" type="image/x-icon">
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
<div class="audit-layout">
    <center><h2>Audit Trail</h2></center>
    <center>
        <table class="table">
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
        </table>
    </center>
</div>

</body>
</html>
