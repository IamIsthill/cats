<?php
try{
    $pdo=new PDO("mysql:host=localhost;dbname=cams","root","");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $exc){
    echo $exc->getMessage();
}
?>