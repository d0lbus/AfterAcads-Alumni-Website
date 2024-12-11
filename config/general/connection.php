// References:
// - GeeksforGeeks: "How to connect to MySQL database using PHP" - https://www.geeksforgeeks.org/how-to-connect-to-mysql-database-in-php/
// - StackOverflow: "PHP MySQL connection with error handling" - https://stackoverflow.com/questions/10914360/how-do-i-connect-to-mysql-with-php
// - YouTube: "PHP MySQL database connection tutorial" - https://www.youtube.com/watch?v=K6FZ8FmdkQU
// - ChatGPT: "PHP mysqli connection and error handling explained" - ChatGPT (2024)

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