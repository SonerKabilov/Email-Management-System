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
	<title>Входяща поща</title>
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
	 			<a class="active" href="inbox.php"><li>&emsp;Входяща поща</li></a>
	 			<a href="mails-sent.php"><li>&emsp;Изпратени</li></a>
	 			<a href="mails-deleted.php"><li>&emsp;Кошче</li></a>
	 			<a href="#"><li>&emsp;Справки</li></a>
	 		</ul>
	 	</div>
	 	<div class="mailbox">

			<?php 
				} else {
					header("location: ../e-mail%20system/login.php");
				}

				require_once "includes/dbh.inc.php";

				$logged_user = $_SESSION["userid"];

				$sql = "SELECT r.message_sender, m.message_id, m.message_subject, t.type_name, m.message_date, s.message_stat
						FROM message_recipients r
						INNER JOIN messages m ON r.message_id = m.message_id
						INNER JOIN message_status s ON r.status_id = s.status_id
						INNER JOIN message_types t ON m.type_id = t.type_id
						WHERE user_id = $logged_user AND message_stat != 'message_deleted'
						ORDER BY message_date DESC";
				$result = mysqli_query($conn, $sql);

				if(mysqli_num_rows($result)) {
					echo "<div class='table'>";
					while ($row = mysqli_fetch_assoc($result)) {
						date_default_timezone_set('Europe/Sofia');

						if(date('Y-m-d') != date('Y-m-d', strtotime($row['message_date']))) {
							$date = date('d/m/Y', strtotime($row['message_date']));
						} else {
							$date = date('H:i', strtotime($row['message_date']));
						}
						echo "<a href='mail.php?id=".$row['message_id']."&from=inbox'><div class='row'>";
							if($row["message_stat"] == "message_unread") {
								echo "<div class='cell message-sender bold'>";
							} else {
								echo "<div class='cell message-sender'>";
							}
								echo $row['message_sender']. "</div>";

							if($row["message_stat"] == "message_unread") {
								echo "<div class='cell message-subject bold'>";
							} else {
								echo "<div class='cell message-subject'>";
							}
							
								echo $row['message_subject'].
								"</div>
								<div class='cell message-type'>";
								if($row['type_name'] == "Работа") {
			            			echo "<button type='button' class='btn btn-mailbox btn-success'>Работа</button>";
			            		} else if($row['type_name'] == "Социални") {
			            			echo "<button type='button' class='btn btn-mailbox btn-info'>Социални</button>";
			            		} else if($row['type_name'] == "Реклама") {
			            			echo "<button type='button' class='btn btn-mailbox btn-warning'>Реклама</button>";
			            		}
								echo "</div>";
							if($row["message_stat"] == "message_unread") {
								echo "<div class='cell bold'>";
							} else {
								echo "<div class='cell'>";
							}
								echo "$date";
							if($row["message_stat"] == "message_unread") {
								echo "</div>";
							} else {
								echo "</div>";
							}
							echo "</div></a>";
			    	}
			    	echo "</div>";
				} else {
					echo "<h5 class='empty'>Папката е празна!</h5>";
				}
			 ?>
 		</div>
	</div>
</body>
</html>