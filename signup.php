<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/signin.css">
    <title>Регистрация</title>
</head>
<body>
    <form action="includes/signup.inc.php" method = "post">
        <h3>Регистрация</h3>
            <div class="input-box">
                <p>Потребителско име</p>
                <input type="text" name="username" required oninvalid="this.setCustomValidity('Моля, попълнете.')" >
            </div>
            <div class="input-box">
                <p>Парола</p>
                <input type="password" name="password" required oninvalid="this.setCustomValidity('Моля, попълнете.')" >
            </div> 
            <div class="input-box">
                <p>Повтори парола</p>
                <input type="password"  name="repeatPassword" required oninvalid="this.setCustomValidity('Моля, попълнете.')" >
            </div>
            <br/>
            <div class="submit-button">
                <input type="submit" name="submit" value="Регистрация">
            </div>
    </form>
</body>
</html>