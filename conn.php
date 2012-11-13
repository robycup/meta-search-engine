<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "engine";

$conn = new mysqli($host,$user,$pass,$db);

if(mysqli_connect_errno()){
    die(mysqli_connect_error());
}
?>
