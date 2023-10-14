<?php
	include "..\..\include\db.php";
	session_start();
	//ежели админу книга не понравилась
	if (isset($_GET['del_book']))
	{
		$id_boock = $_GET['boock_id'];
		echo "ID книги которую вы хотели удалить: $id_boock<br>";
		if (isset($_POST['true']))
		{
			$id = $_POST['id'];
			$sql_del = mysqli_query($connection, "DELETE FROM books WHERE `books`.`id` = '$id'");
			if (!$sql_del)
			{
				echo 'Ошибка удаления!';
			}
			else
			{
				echo 'GGWP!';
				header('Location: ../index.php');
			}
		}
		//там формочка для книженций
	?>
	Введите ID книги:
	<form method = "POST">
		<input type = "text" name = "id">
		<input type = "submit" name = "true">
	</form><hr><?php
?>
<?php
	//тоже вывод книг, авторов, и ID'шников
	$sql = mysqli_query($connection, "SELECT * FROM `books`");
	while ($record = mysqli_fetch_assoc($sql))
	{?> Автор: <strong><?= $record['author'];?></strong><br>
		ID книиги: <strong><?=$record['id']?></strong><br>
		Название: <?= $record['name'];?><br>
		<?= $record['substance'];?><hr>
<?php }}

	//дрочим юзерам
	
	if (isset($_GET['ban']))
	{
		//ежели порнуху скинули в книгу, и не один раз. ну или кому-то делать нехуй, и кого-то решили до админа повысить
		$id_author = $_GET['author_id'];
		echo "Ник автора которого вы хотели изменить: $id_author<br>";
		if (isset($_POST['true']) && $_POST['role'] != 'ban')
		{
			$role = $_POST['role'];
			$id = $_POST['id'];
			$sql_up = mysqli_query($connection, "UPDATE `users` SET `role` = '$role' WHERE `users`.`login` = '$id'");
			if (!$sql_up){
				echo 'Ошибка удаления!';
			}
			else{ //тупо повысить, понизить, и писать запретить
				echo 'GGWP!';
				header('Location: ../index.php');
			}
		}
		elseif (isset($_POST['true']) && $_POST['role'] == 'ban')
		{
			//к хуям стереть аккаунт
			//да, потом надо будет припидорить запись мыла в базу забаненых...
			$id = $_POST['id'];
			$sql_del = mysqli_query($connection, "DELETE FROM users WHERE `users`.`login` = $id");
			if (!$sql_del){
				echo 'Ошибка удаления!';
			}
			else{
				echo 'GGWP!';
				header('Location: ../index.php');
			}
		}
		//в форме и так понятно что к чему
	?>
	Введите ID автора:
	<form id = "data" method = "POST">
		<input type = "text" name = "id">
		<select name = "role" form = "data">
			<option value = "1">Администратор</option>
			<option value = "2">Пользователь</option>
			<option value = "4">Запретить написание книг</option>
			<option value = "ban">Удалить аккаунт</option>
		</select><br>
		<input type = "submit" name = "true">
	</form><hr><?php
?>
<?php
	$sql_usr = mysqli_query($connection, "SELECT id, login, role FROM `users`");
	while ($record = mysqli_fetch_assoc($sql_usr))
		{
		if ($record['role'] != 3){
			if($record['role'] == 2){
				$role = 'Пользователь';
			}
			if($record['role'] == 1){
				$role = 'Администратор';
			}
			if($record['role'] == 4){
				$role = 'Запрет написания книг';
			}?>
			ID: <strong><?= $record['id'];?></strong><br>
			Автор: <strong><?= $record['login'];?></strong><br>
			Роль: <?= $role;?><hr>
<?php }}} 

	//репортики
	
	if (isset($_GET['reports']))
	{
		//удалить все не актуальные репорты
		echo "<form method = 'POST'>
			Удалить все неактуальные жалобы 
			<input type = 'submit' name = 'dell_null' value = 'Удалить'>
		</form><hr>";
		//код удаления
		if (isset($_POST['dell_null'])){
			$sql_del = mysqli_query($connection, "DELETE FROM reports WHERE `actual` = 0");
		}
		//просмотр всего остального...
		$sql_report = mysqli_query($connection, "SELECT * FROM `reports` ORDER BY `reports`.`actual` ASC");
		while ($record = mysqli_fetch_assoc($sql_report))
		{ ?>
			Тема жалобы: <?=$record['teheme'];?>&emsp;
			Оставлена пользователем: <?=$record['reporter'];?><br>
			Текст жалобы: <?=$record['text'];?><br>
			Актуальность: <?php 
			if($record['actual'] == 1) {echo 'Актуально<br>';}
			elseif ($record['actual'] == 0) {echo 'Неактуально<br>';}
			echo '<details><summary>Жалоба оставлена на</summary>';
			//смотрим на какую книгу жалоба
			if($record['id_book'] != 0){
				$id_book = $record['id_book'];
				$sql_book = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM books WHERE `id` LIKE '$id_book'")); 
				$sql_book_id = $sql_book['id'];
				$id_author = $sql_book['author'];?>
				ID книги: <b><?=$sql_book_id;?></b>&emsp;
				Автор: <b><?=$id_author;?></b><br>
				Название: <b><?=$sql_book['name'];?></b><br>
				Описание: <?=$sql_book['substance'];?><br>
				<details><summary>Текст</summary><?=$sql_book['text'];?></details>
				</details>
				<details><summary>Действия</summary>
				<form method = "POST" id = "do_boock">
					<input type = "hidden" name = "usr_auth" value = "<?=$id_author;?>">
					<input type = "hidden" name = "id_report" value = "<?=$record['id'];?>">
					<input type = "hidden" name = "id_boock" value = "<?=$sql_book_id;?>">
					<input type = "submit" name = "dell_comm" value = "Удаллить">
				</form></details><hr>
				
			<?php 
			}
			//смотрим на какой коммент жалоба
			if($record['id_comment'] != 0){
				$id_comment = $record['id_comment'];
				$id_comm = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM comments WHERE `id` LIKE '$id_comment'")); 
				$sql_comm_id = $id_comm['id'];
				$id_author = $id_comm['user_name'];?>
				ID комментария: <b><?=$sql_comm_id;?></b>&emsp;
				Автор: <b><?=$id_author;?></b>&emsp;
				ID автора: <b><?=$id_comm['user_id'];?></b><br>
				Текст: <?=$id_comm['comment'];?>
				</details>
				<details><summary>Действия</summary>
				<form method = "POST" id = "do_comment">
					<input type = "hidden" name = "usr_auth" value = "<?=$id_author;?>">
					<input type = "hidden" name = "id_report" value = "<?=$record['id'];?>">
					<input type = "hidden" name = "id_comment" value = "<?=$sql_comm_id;?>">
					<input type = "submit" name = "dell_comm" value = "Удаллить">
				</form></details><hr>
				<?php
			} 
		}
		if (isset($_POST['dell_comm'])){
			var_dump($_POST);
		}
	}
?>