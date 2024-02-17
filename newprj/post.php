<?php
session_start();

require_once './include/connect/dbcon.php';

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $sql="SELECT * FROM userlist WHERE username=:username";
        $result = $pdo->prepare($sql);
        $result->bindParam(':username', $_SESSION["Username"]);
        $result->execute();
        $user=$result->fetch();

        // Include the QR Code library
require_once './phpqrcode/qrlib.php';

function encodeImageToBase64($saveqr)
{
    $imageData = file_get_contents($saveqr);
    return base64_encode($saveqr);
}

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

$path = "images/qr/";
$saveqr = $path . time() . ".png";
$qrcode=time().".png";
$upload=$path . $qrcode;

// Generate QR code and save the qr
QRcode::png("cams", $saveqr, "H", 10, 10);

$sql="INSERT INTO postlist(user_id,event,date,location,qr) VALUES(:user_id,:event,:date,:location,:qr)";
$result = $pdo->prepare($sql);
$result->bindParam(':user_id', $user["id"]);
$result->bindParam(':event', $_POST["event"]);
$result->bindParam(':date', $_POST["date"]);
$result->bindParam(':location', $_POST["location"]);
$result->bindParam(':qr', $upload);
$result->execute();
    }
    
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
    <title>CATS</title>
    <link rel="stylesheet" href="./include/style/loginstyle.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
</head>
<body>
    <div class="header">
        <div class="left-section">
          <img src="images/logos/newlogo.png" alt="">
          <div class="webname">Church Attendance Tracking System</div>
        </div>
      </div>
      
    <div class="form">
        <h2>Post Here</h2>
        <form method="POST" action="">
            <input type="text" name="event" placeholder="Enter Event Here" required>
            <input type="date" name="date" placeholder="Enter Date Here" required>
            <input type="text" name="location" placeholder="Enter Location Here" required>
            <button class="btnn" type="submit" name="post" >Post</button>
        </form>
    </div>
</body>
</html>