<?php
if(isset($_POST["submit"])){
    $username=$_POST["username"];
    $password=$_POST["password"];
    $repeatPassword=$_POST["repeatPassword"]; 

    require_once "dbh.inc.php";
    require_once "functions.php";

    if(emptyInput($username, $password, $repeatPassword) !== false) {
        header("location: ../signup.php?error=emptyinput");
        exit();
    }
    if(invalidUsername($username) !== false){
        header("location: ../signup.php?error=username");
        exit();
    }
    if(passwordMatch($password, $repeatPassword) !== false){
        header("location: ../signup.php?error=passwordmatch");
        exit();
    }
    if(usernameTakenCheck($conn, $username) !== false){
        header("location: ../signup.php?error=usernametaken");
        exit();
    } 

    createUser($conn, $username, $password);
}
else {
    header("location: ../signup.php");
    exit();
}