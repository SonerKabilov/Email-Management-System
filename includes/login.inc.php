<?php
if(isset($_POST["submit"])){
    $username=$_POST["username"];
    $password=$_POST["password"];


    require_once "dbh.inc.php";
    require_once "functions.php";

    if(emptyInputLogin($username, $password)) {
    	header("location: ../login.php?error=emptyinput");
		exit();
    }

    loginUser($conn, $username, $password);
    
    header("location: ../index.php?error=none");
}
else {
    header("location: ../login.php");
    exit();
}
