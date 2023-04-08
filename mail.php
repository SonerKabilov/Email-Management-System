<!DOCTYPE html>
<?php 
	session_start();

	require_once "includes/dbh.inc.php";

	$id = mysqli_real_escape_string($conn, $_GET["id"]);
	$from = mysqli_real_escape_string($conn, $_GET["from"]);

	if($_SESSION['userid']) {

 ?>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<title>Document</title>
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
	 			<a href="mails-deleted.php"><li>&emsp;Кошче</li></a>
	 			<a href="#"><li>&emsp;Справки</li></a>
	 		</ul>
	 	</div>
	 	<div class="mail">
	 		<form action="#" method="post">
				<input type='submit' name="submit" value='Изтрий'><hr/>
			</form>
			<?php 
				} else {
					header("location: ../e-mail%20system/login.php");
				}

				if($_SERVER['HTTP_REFERER'] == "http://localhost/phpcodes/e-mail%20system/inbox.php") {
					$sql_update = "UPDATE message_recipients SET status_id = '2' WHERE message_id = '$id'";
					mysqli_query($conn, $sql_update);
				}

				require_once "includes/dbh.inc.php";

				$sql = "SELECT u.user_username, r.message_sender, m.message_subject, m.message_text, t.type_name, m.message_date
						FROM messages m
						INNER JOIN message_recipients r ON r.message_id = m.message_id
						INNER JOIN message_types t ON m.type_id = t.type_id
						INNER JOIN users u ON r.user_id = u.user_id
						WHERE m.message_id = '$id';";
				$result = mysqli_query($conn, $sql);
            	$queryResults = mysqli_num_rows($result);

            	if($queryResults > 0) {
            		$row = mysqli_fetch_assoc($result);
            		if($row['type_name'] == "Работа") {
            			echo "<button type='button' class='btn btn-success'>Работа</button>";
            		} else if($row['type_name'] == "Социални") {
            			echo "<button type='button' class='btn btn-info'>Социални</button>";
            		} else if($row['type_name'] == "Реклама") {
            			echo "<button type='button' class='btn btn-warning'>Реклама</button>";
            		}
            		
					echo "<h5>".$row['message_subject']."</h5>";
					echo
						"<table>
							<tr>
								<td>От: </td>
								<td>".$row['message_sender']."</td>
							</tr>
							<tr>
								<td>До: </td>
								<td>".$row['user_username']."</td>
							</tr>
							<tr>
								<td>Дата: </td>
								<td>".$row['message_date']."</td>
							</tr>
						</table> <br/>
						".$row['message_text'];
				}

				if(isset($_POST["submit"])) {
					if($from == "inbox") {
						$sql_update = "UPDATE message_recipients SET status_id = '3' WHERE message_id = '$id'";
						mysqli_query($conn, $sql_update);
					} else if($from == "sent") {
						$sql_update = "UPDATE message_senders SET status_id = '3' WHERE message_id = '$id'";
						mysqli_query($conn, $sql_update);
					}
					
					header("location: ./inbox.php");
				}
			 ?>
 		</div>
	</div>
</body>
</html>