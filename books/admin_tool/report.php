<?php
	include "..\..\include\db.php";
	session_start();
	if (isset($_POST['report_boock']) || (isset($_GET['prof']) && $_GET['theme'] == 'Жалоба на книгу'))
	{
		if (isset($_GET['prof']))
		{
			$teheme = $_GET['theme'];
			$reporter = $_GET['usr_id'];
			$boock_id = $_GET['boock_id'];
			$text = htmlspecialchars($_GET['report']);
			$sql_boock = mysqli_prepare($connection, "INSERT INTO `reports` (`id`, `teheme`, `text`, `reporter`, `id_book`, `id_comment`, `actual`) VALUES (NULL, ?, ?, ?, ?, 0, 1);");
			mysqli_stmt_bind_param($sql_boock, 'sssi', $teheme, $text, $reporter, $boock_id);
			$result = mysqli_stmt_execute($sql_boock);
			if ($result){
				header('Location: ../index.php');
			}
		}
	}
	if (isset($_POST['report_boock']) || (isset($_GET['prof']) && $_GET['theme'] == 'Жалоба на комментарий'))
	{
		if (isset($_GET['prof']))
		{
			$teheme = $_GET['theme'];
			$reporter = $_GET['usr_id'];
			$comment_id = $_GET['comment_id'];
			$text = htmlspecialchars($_GET['report']);
			$sql_comment = mysqli_prepare($connection, "INSERT INTO `reports` (`id`, `teheme`, `text`, `reporter`, `id_book`, `id_comment`, `actual`) VALUES (NULL, ?, ?, ?, 0, ?, 1);");
			mysqli_stmt_bind_param($sql_comment, 'sssi', $teheme, $text, $reporter, $comment_id);
			$result = mysqli_stmt_execute($sql_comment);
			if ($result){
				header('Location: ../index.php');
			}
		}
	}
?>
<form method = "GET">
	<input type = "hidden" name = "theme" value = "<?=$_POST['theme'];?>">
	<input type = "hidden" name = "usr_id" value = "<?=$_SESSION['login']?>">
	<input type = "hidden" name = "boock_id" value = "<?=$_POST['id_book']?>">
	<input type = "hidden" name = "comment_id" value = "<?=$_POST['id_comment']?>">
	<p><textarea style="width:360px; height:150px;" name = "report" required placeholder = "Ваша жалоба..."></textarea> 
	<input type = "submit" value = "Отправить" name = "prof"></p>
</form>