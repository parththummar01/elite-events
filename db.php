<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "event_management";


$conn = mysqli_connect($host,$user,$pass,$db);

if($conn){
    echo "connected";
}


?>