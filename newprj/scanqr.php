
<?php
session_start();


require_once './include/connect/dbcon.php';
// Include the QR Code library
require_once './phpqrcode/qrlib.php';



$user_id = $_SESSION["user_id"];

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT * FROM userlist WHERE id=:user_id";
    $result = $pdo->prepare($sql);
    $result->execute([":user_id" => $user_id]);
    $user = $result->fetch();
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
    <title>CAMS</title>
    <script src="./node_modules/html5-qrcode/html5-qrcode.min.js"></script>
    <link rel="stylesheet" href="userstyle.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <style>
        main {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        #reader {
            width: 600px;
            
        }
        #result {
            text-align: center;
            font-size: 1.5rem;
        }
    </style>
</head>

<body>
<div class="header">
            <div class="left-section">
                <a href="userhomepage.php" id="logo-link">
                    <img src="images/logos/newwhitetranspa.png" alt=""> 
                </a>
            </div>
            
            <div class="right-section">
                <a href="home.php" class="dashh">Dashboard</a>
                <a href="userorg.php" class="orgs">Organization</a>
                <a href="audit.php" class="audtrail" >Audit Trail</a>
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
            
             
              <a class="logout" href=logout.php">Log out</a>
            </div>
          </div>
    <main>
        <div id="reader"></div>
        <div id="result"></div>
    </main>

    <script>
    const scanner = new Html5QrcodeScanner('reader', {
        qrbox: {
            width: 250,
            height: 250,
        },
        fps: 20,
    });

    scanner.render(success, error);

    function success(result) {
        console.log(result);

        scanner.clear();

        // Use AJAX to send the scanned QR code data to the server
        const xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    document.getElementById('result').innerHTML = `<h2>${xhr.responseText}</h2>`;
                    // Redirect to userattendance.php
                    window.location.href = 'userattendance.php';
                } else {
                    console.error('Error:', xhr.statusText);
                }
            }
        };

        xhr.open('GET', `process_qr.php?result=${result}`, true);
        xhr.send();
    }

    function error(err) {
        console.log(err);
    }
</script>


   
</body>
</html>


