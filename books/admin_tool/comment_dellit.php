<?php 
	include "..\..\include\db.php";
	session_start();
	$id = $_POST['id_comment'];
	$sql_comment = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `comments` WHERE `id` = '$id'"));
	$id_sql_usr = $sql_comment['user_id'];
	if (isset($_GET['delit']))
	{
		$usr_id = $_GET['user_id'];
		$comm_id = $_GET['comm_id'];
		$sql = mysqli_query($connection, "DELETE FROM `comments` WHERE `comments`.`id` = '$comm_id'");
		if ($sql)
		{
			header('Location: ../index.php');
		}
		else
		{
			echo "ошибка";
		}
		//$sql_report = mysqli_query($connection, "INSERT INTO `reports` ");
	}
?>
<div>
	выбранный комментарий: <?= $sql_comment['comment']?><br>
	оставлен пользователем: <?= $sql_comment['user_name']?><br>
	добавлен: <?= $sql_comment['date']?><br><hr>
</div>
<div>
	<form method = "GET">
		хотите удалить комментарий?
		<input type = "hidden" name = "usr_id" value = "<?=$id_sql_usr?>">
		<input type = "hidden" name = "comm_id" value = "<?=$id?>">
		<input type = "submit" value = "Удалить" name = "delit">
	</form>
</div>