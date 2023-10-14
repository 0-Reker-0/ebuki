<?php
	include "..\include\db.php";
	session_start();
	$login = $_SESSION['login'];
	$boock_id1 = $_POST['boock_id'];
	$user_id_sql = mysqli_fetch_assoc(mysqli_query($connection, "SELECT `id` FROM `users` WHERE `login` LIKE '$login'"));
	if(isset($_POST['submit']))
	{
		$user_id = $user_id_sql['id'];
		$comment = htmlspecialchars($_POST['comment']);
		$boock_id = $_POST['boock_id'];
		$date = date("Y-m-d H:i:s");
		$error = array();
		if (strlen($comment) > 255)
		{
			$error[] = 'Слишком длинный комментарий!<br>Максимум 225 символов!';
		}
		if (empty($error))
		{
			$sql = mysqli_prepare($connection, "INSERT INTO `comments` (`id`, `boock_id`, `user_id`, `comment`, `date`, `user_name`) VALUES (NULL, ?, ?, ?, ?, ?);");
			mysqli_stmt_bind_param($sql, 'sssss', $boock_id, $user_id, $comment, $date, $login);
			$result = mysqli_stmt_execute($sql);
			if ($result){
				header("Location: index.php");
			}
			else{
				echo '<hr>Ошибка отправки! Комментарий не добавлен.<hr>';
			}
		}
		else{
			echo "<hr>".array_shift($error)."<hr>";
		}
	}
?>
<form id = "comment_add" method = "POST">
	Ваш комментарий:
	<p><textarea style="width:360px; height:150px;" name = "comment" required placeholder = "Добавить комментарий..."></textarea></p>
	<input type = "hidden" name = "boock_id" value = "<?=$boock_id1?>">
	<input type = "submit" name = "submit" value = "Добавить">
</form>