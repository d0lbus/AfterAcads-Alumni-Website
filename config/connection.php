<?php 
$host="localhost";
$user="root";
$pass="";
$db="afteracads";
$conn=new mysqli($host,$user,$pass,$db);


if($conn->connect_error){
    echo "failed to connect Database".$conn->connect_error;
}


?>