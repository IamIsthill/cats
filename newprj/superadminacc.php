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
    <link rel="stylesheet" href="superadminacc.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round|Open+Sans">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
        var actions = $("table td:last-child").html();
       
        // Delete row on delete button click
        $(document).on("click", ".delete", function(e){
            e.preventDefault(); // Prevent the default behavior of the anchor tag

            var deleteUrl = $(this).attr('href');
            var row = $(this).closest("tr"); // Get the closest row to remove

            // Perform an AJAX request to the delete_user.php file
            $.ajax({
                type: "GET",
                url: deleteUrl,
                success: function(response) {
                    // If the deletion is successful, remove the row from the table
                    row.remove();
                },
                error: function(error) {
                    console.error("Error deleting user:", error);
                }
            });
        });
    </script>

</head>
    <body>
          <div class="header">
            <div class="left-section">
                <a href="superadminhomepage.php" id="logo-link">
                    <img src="images/logos/newwhitetranspa.png" alt=""> 
                </a>
            </div>
            
            <div class="right-section">
                <a href="superadminhomepage.php" class="dashh">Dashboard</a>
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
<div class="container-lg">
    <div class="table-responsive">
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-8"><h2>Account Lists</h2></div>
                    
                </div>
            </div>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Organization</th>
                        <th>Date Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <<?php foreach($userlist as $list): ?>
    <tr>
        <td><?php echo $list['username']; ?></td>
        <td><?php echo $list['organization']; ?></td>
        <td><?php echo $list['account_created']; ?></td>
        <td>
            <a class="delete" title="Delete" data-toggle="tooltip" href="superadmindeleteuser.php?id=<?php echo $list['id']; ?>"><i class="material-icons">&#xE872;</i></a>
        </td>
    </tr>
<?php endforeach; ?>

                </tbody>
            </table>
        </div>
    </div>
</div>     
    </body>
</html>