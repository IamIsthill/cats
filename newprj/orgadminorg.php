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
    <link rel="icon" type="image/png" href="images/logos/newlogo.png">
    <title>CATS
    </title>
    <link rel="stylesheet" href="orgadminorg.css">
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
                  <a href="orgadminprofile.php" target="_blank"> <?php echo $user["username"]; ?> </a> <!-- <?php echo $post['username']; ?> -->
              </div>
      
              <a class="logout" href="logout.php">Log out</a>
            </div>
          </div>

          <section class="orgtable">
            <div class="container">
                <div class="text-title">
                  <h2 class="heading-section"><?php echo $user["organization"]; ?></h2>
                </div>
                <div class="add-user"> <a href="orgadduser.php">Add User</a> </div>
                  <div class="table-wrap">
                    <table class="ta table" >
                      <thead class="thead-primary">
                        <tr>
                          <th>Ranking</th>
                          <th>Name</th>
                          <th>Attendance</th>
                          <th>Contact</th>
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
          
    </body>
</html>