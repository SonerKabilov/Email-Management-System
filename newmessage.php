<!DOCTYPE html>
<?php 
	session_start();
	if($_SESSION['userid']) {
 ?>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<title>Ново съобщение</title>
</head>
<body>
	<header>
	 	<div class="left">
	 		<h2>BGmail</h2>
	 	</div>
	 	<div class="right">
	 		<div class="dropdown">
	 			<button class="dropbtn">
	 				<?php  
		 				echo $_SESSION['userusername']."@bgmail.com";
		 			?>
	 			</button>
	 			<div class="dropdown-content">
	 				<p class="hide-element">
		 				<?php  
			 				echo $_SESSION['userusername']."@bgmail.com";
			 			?>
		 			</p>
		 			<hr class="hide-element">
		 			<a href="#">Профил</a>
		 			<a href="includes/logout.inc.php">Изход</a>
	 			</div>
	 		</div>
	 	</div>
	 </header>

	 <div class="content">
	 	<div class="navigation">
	 		<ul>
	 			<a href="newmessage.php"><li class="newmessage color-white">Ново съобщение</li></a>
	 			<hr class="color-white">
	 			<a href="inbox.php"><li>&emsp;Входяща поща</li></a>
	 			<a href="mails-sent.php"><li>&emsp;Изпратени</li></a>
	 			<a href="#"><li>&emsp;Кошче</li></a>
	 			<a href="#"><li>&emsp;Справки</li></a>
	 		</ul>
	 	</div>
	 	<div class="new-message">
	 		<form action="#" method="post" enctype="multipart/form-data">
	 			<div class="top-buttons">
		 			<input class='submit' type="submit" name="submit" value="Изпрати">
		 			<p>Прикачи</p>
	 			</div>
		 		<div class="message-body">
		 			<table>
		 				<tr><td>До:</td><td> <input type="text" name="recipient" required></td></tr>
		 				<tr><td>Заглавие:</td><td> <input type="text" name="subject" required></td></tr>
		 				<tr>
		 					<td>Тип на e-mail</td>
		 					<td>
		 						<select name="message_type" required>
			                        <option value="" selected disabled hidden>Изберете тип</option>
			                        <option value="social">Социални</option>
			                        <option value="advertisement">Реклама</option>
			                        <option value="work">Работа</option>
	                    		</select>
	                    	</td>
	                    </tr>
		 			</table>
		 			<br>
		 			<textarea name="text"></textarea>
		 		</div>
	 		</form>
	 	</div>
	 </div>
</body>
</html>
<?php 
	} else {
		header("location: ../e-mail%20system/login.php");
	}

	require_once "includes/dbh.inc.php";

	if(isset($_POST["submit"])) {
		$sender_id = $_SESSION["userid"];
		$sender_username = $_SESSION['userusername'];
		$recipient = $_POST["recipient"];
		$subject = $_POST["subject"];
		$text = $_POST["text"];
		// if($_POST["attachment"] != null) {
		// 	$attachment = $_POST["attachment"];
		// } else {
		// 	$attachment = " ";
		// }

		if($_POST["message_type"] == "social") {
			$type = 1;
		} else if($_POST["message_type"] == "advertisement") {
			$type = 2;
		} else if($_POST["message_type"] == "work"){
			$type = 3;
		}

		date_default_timezone_set("Europe/Sofia");
		$date = date("d/m/Y h:i");

		$sql = "INSERT INTO messages (message_subject, message_text, type_id) VALUES ('$subject', '$text', '$type')";
		mysqli_query($conn,$sql);
		$last_message_id = mysqli_insert_id($conn);
		$sql = "INSERT INTO message_senders (message_recipient, message_id, user_id) VALUES ('$recipient', '$last_message_id', '$sender_id')";
		mysqli_query($conn,$sql);
		$sql_select = "SELECT user_id, user_username FROM users";
		$result = mysqli_query($conn, $sql_select);
		if(mysqli_num_rows($result)) {
			while ($row = mysqli_fetch_assoc($result)) {
    			if($row["user_username"] == $recipient) {
    				$recipient_id = $row["user_id"];
    				$sql = "INSERT INTO message_recipients (message_sender, user_id, message_id) VALUES ('$sender_username', '$recipient_id', '$last_message_id')";
    				mysqli_query($conn,$sql);
    			}
			}
		}
	}
 ?>
