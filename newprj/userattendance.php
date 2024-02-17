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

    $sql = "SELECT * FROM postlist INNER JOIN attached_org ON postlist.post_id=attached_org.post_id WHERE org=:organization AND postlist.date <= CURRENT_DATE() ";
    $result = $pdo->prepare($sql);
    $result->execute(['organization' => $user['organization']]);

    $post = $result->fetchAll(PDO::FETCH_ASSOC);

    $sql = "SELECT * FROM attendance WHERE user_id=:user_id";
    $result = $pdo->prepare($sql);
    $result->execute(['user_id' => $user['id']]);

    $attendance = $result->fetchAll(PDO::FETCH_ASSOC);

    // Update org_attendance field
    $sql = "UPDATE userlist SET org_attendance = :org_attendance  WHERE id=:user_id";
    $result = $pdo->prepare($sql);
    $result->execute([":user_id" => $user_id]);

    // Fetch the updated user details
    $sql = "SELECT * FROM userlist WHERE id=:user_id";
    $result = $pdo->prepare($sql);
    $result->execute([":user_id" => $user_id]);
    $user = $result->fetch();

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
    <title>CATS</title>
    <link rel="stylesheet" href="userattendance.css">
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
            <a href="audit_trail.php" class="audtrail">Audit Trail</a>
            <a href="userattendance.php" class="ah">Attendance History</a> <!-- edited -->
            <a href="scanqr.php" class="scanqr">Scan QR</a>
            <div class="profile-link">
                <a href="userprofile.php" target="_blank">
                    <img class="profile-picture" src="<?php echo $user["profile_picture"]; ?>" alt="">
                </a>
            </div>
            <div class="user-name">
                <a href="userprofile.php" target="_blank"><?php echo $user["username"]; ?></a>
            </div>
            <a class="logout" href="logout.php">Log out</a>
        </div>
    </div>

    <section class="orgtable">
        <div class="container">
            <div class="text-title">
                <h2 class="heading-section">Attendance History</h2>
            </div>
            <div class="table-wrap">
                <table class="ta table">
                    <thead class="thead-primary">
                        <tr>
                            <th>Event</th>
                            <th>Date</th>
                            <th>Location</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $count = 1; ?>
                        <?php foreach ($post as $log): ?>
                            <tr data-row-id="<?php echo $count; ?>">
                                <td><?php echo $log["event"]; ?></td>
                                <td><?php echo $log["date"]; ?></td>
                                <td><?php echo $log["location"]; ?></td>
                                <td>
                                    <?php
                                        $attended = false;
                                        foreach($attendance as $attend) {
                                            if($attend["post_id"] == $log["post_id"]) {
                                                echo $attend["remarks"];
                                                $attended = true;
                                                break; // No need to continue checking once attendance is found
                                            }
                                        }
                                        if (!$attended) {
                                            echo "Missed";
                                        }
                                    ?>
                                </td>
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
