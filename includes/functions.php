<?php 
    function emptyInput($username, $password, $repeatPassword) {
        $result;
        if(empty($username) || empty($password) || empty($repeatPassword)){
            $result=true;
        }
        else{
            $result=false;
        }
        return $result;
    }

    function invalidUsername($username) {
        $result;
        if(!preg_match("/^[a-zA-Z0-9]*$/", $username)){
            $result=true;
        }
        else{
            $result=false;
        }
        return $result;
    }

    function passwordMatch($password, $repeatPassword){
        $result;
        if($password !== $repeatPassword){
            $result=true;
        }
        else{
            $result=false;
        }
        return $result;
    }

    function usernameTakenCheck($conn, $username){
        $sql = "SELECT * FROM users WHERE user_username = ?;";
        $stmt = mysqli_stmt_init($conn);
        
        if(!mysqli_stmt_prepare($stmt, $sql)) {
            header("location: ../signup.php?error=stmtfailed");
            exit();
        }

        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);

        $sqlResult = mysqli_stmt_get_result($stmt);
        $result;

        if($row = mysqli_fetch_assoc($sqlResult)) {
            return $row;
        }
        else {
            $result = false;
            return $result;
        }

        mysqli_stmt_close($stmt);
    }

    function createUser($conn, $username, $password){
        $sql = "INSERT INTO users(user_username, user_password) VALUES(?,?);";
        $stmt = mysqli_stmt_init($conn);
        
        if(!mysqli_stmt_prepare($stmt, $sql)) {
            header("location: ../signup.php?error=stmtfailed");
            exit();
        }

        $hashedpassword=password_hash($password,PASSWORD_DEFAULT);

        mysqli_stmt_bind_param($stmt, "ss", $username, $hashedpassword);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        header("location: ../login.php?error=none");
        exit();
    }

    function emptyInputLogin($username, $password){
        $result;
        if(empty($username) || empty($password)){
            $result=true;
        }
        else{
            $result=false;
        }
        return $result;
    }

    function loginUser($conn, $username, $password) {
        $usernameTaken = usernameTakenCheck($conn, $username);

        if($usernameTaken === false) {
            header("location: ../login.php?error=wronglogin");
            exit();
        }

        $passwordHashed = $usernameTaken["user_password"];
        $checkPwd = password_verify($password, $passwordHashed);

        if($checkPwd === false) {
            header("location: ../login.php?error=wrongpassword");
            exit();
        }
        else if($checkPwd === true) {
            session_start();
            $_SESSION["userid"] = $usernameTaken["user_id"];
            $_SESSION["userusername"] = $usernameTaken["user_username"];

            header("location: ../inbox.php");
            exit();
        }
    }