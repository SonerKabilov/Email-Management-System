<?php

$host= 'localhost';
$dbUser= 'root';
$dbPass= '';
$dbName= 'email';

$conn = mysqli_connect($host, $dbUser, $dbPass, $dbName);

if(!$conn){
	die("Connection failed".mysql_connect_error());
}