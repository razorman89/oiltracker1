<?php

$hostname = "localhost";
$username = "root";
$password = "7920979v";
$dbname = "project2013";



$db = mysqli_connect($hostname , $username, $password, $dbname);

// Check connection
if (mysqli_connect_errno($db))
{
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

?>
