<?php
session_start();

require_once './include/connect/dbcon.php';

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        include 'access.php';
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
    <link rel="stylesheet" href="include/style/loginstyle.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
</head>
<body>
    <div class="header">
        <div class="left-section">
          <img src="images/logos/blacknewtrans.png" alt="">
          
        </div>
      </div>
      
    <div class="form">
        <h2>Login Here</h2>
        <form method="POST" action="">
            <input type="text" name="Username" placeholder="Enter Username Here" required>
            <input type="password" name="Password" placeholder="Enter Password Here" required>
            <button class="btnn" type="submit" name="login" >Login</button>
        </form>
        
    </div>
</body>
</html>
