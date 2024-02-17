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
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT * FROM userlist WHERE id=:user_id";
    $result = $pdo->prepare($sql);
    $result->execute([":user_id" => $user_id]);
    $user = $result->fetch();


    $sql = "SELECT * FROM userlist WHERE organization=:organization";
    $result = $pdo->prepare($sql);
    $result->execute(['organization' => $user['organization']]);

    $userorg = $result->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $exc) {
    $message = $exc->getMessage();
    // Debugging: Add the following line to display the exception message
    echo "Exception: $message";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organization</title>
    <link rel="stylesheet" href="userorg.css">
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

<section class="orgtable">
    <div class="container">
        <div class="text-title">
            <h2 class="heading-section">Org #1</h2>
        </div>
        <div class="table-wrap">
            <table class="ta table">
                <thead class="thead-primary">
                <tr>
                    <th>Rankings</th>
                    <th class="name" data-unsortable>Name</th>
                    <th>Attendance</th>
                    <th class="cont" data-unsortable>Contact </th>
                </tr>
                </thead>
                <tbody>
                <?php $count = 1; ?>
                <?php foreach ($userorg as $log): ?>
                    <tr data-row-id="<?php echo $count; ?>">
                        <td><?php echo $count; ?></th>
                        <td><?php echo $log["username"]; ?></td>
                        <td>30</td>
                        <td>0<?php echo $log["contact"]; ?></td>
                    </tr>
                    <?php $count++; ?>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<script src="js/table-actions.min.js"></script>
<script>
    new TableActions("table", {sortable: true});
</script>

</body>
</html>
