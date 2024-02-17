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


    $sql = "SELECT * FROM userlist WHERE access = :access";
    $result = $pdo->prepare($sql);
    $result->bindValue(':access', "orgadmin");
    $result->execute();

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
    <link rel="stylesheet" href="cadminorg.css">
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
          <section class="orgtable">
            <div class="container">
                <div class="text-title">
                  <h2 class="heading-section">Organizations</h2>
                </div>
                <div class="add-user"> <a href="caddorg.php">Add Organization</a> </div>
                  <div class="table-wrap">
                    <table class="ta table" >
                      <thead class="thead-primary">
                        <tr>
                          <th>Ranking</th>
                          <th>Organization Name</th>
                          <th>Total Attendance</th>
                          
                        </tr>
                      </thead>
                      <tbody>
                      <?php $count = 1; ?>
                <?php foreach ($userorg as $log): ?>
                    <tr data-row-id="<?php echo $count; ?>">
                        <td><?php echo $count; ?></th>
                        <td><?php echo $log["username"]; ?></td>
                        <td>30</td>
                        
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