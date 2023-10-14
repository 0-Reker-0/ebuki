<!DOCTIPE html>
<div>
<?php
	//отут пиздец дрочи
	include "..\include\db.php";
	session_start();
	//проверочка на то, что можно, чего нельзя
	$role = $_SESSION['role'];
	$banned = $role == 4;
	echo 'Твой логин: '.$_SESSION['login'].'<br>';
	if ($banned) {echo 'Вам запрещено писать комментарии, и создавать книги!';}
	elseif ($role == 2) {echo 'Твоя роль: пользователь';}
	elseif ($role == 1 || 3) {echo 'Твоя роль: администратор'; ?>
	<form action = "./admin_tool/admin.php" method = "GET">
		<input type="submit" name = "reports" value = "Просмотр жалоб">
	</form> <?php }
	if (!$banned) { //если не забанили
?>
<hr><form action = "book_editor.php">
	Добавить книгу:
	<input type = "submit" value = "Добавить">
</form>
<div>
<form method = "POST">
	Поиск по темам:
	<input type = "text" placeholder = "Напишите тему" name = "theme_serch">
	<br>Поиск по названию:
	<input type = "text" placeholder = "Напишите название" name = "name_serch">
	<br>Поиск по автору:
	<input type = "text" placeholder = "Напишите ник" name = "auth_serch">
	<input type = "submit" value = "Поиск" name = "serch">
</form><hr>
</div>
<?php } //кнопушка чтоб выйти есть у всех ?>
<div>
<form method = "GET">
	<strong>Выйти с аккаунта</strong>
	<input type = "submit" value = "Выход" name = "exit">
	<?php 
		if (isset($_GET['exit']))
		{
			//долго я ебался, но оно работает
			session_destroy();
			setcookie("auth[role]", "",time()-3600,"/EBooki");
			setcookie("auth[name]", "",time()-3600,"/EBooki");
			header('Location: ../index.php');
		}
	?>
</form><hr>
</div>
Ссылочка на донуты :3<br>
https://www.donationalerts.com/c/jmishenko_abobus <br>Или воспользуйтесь QR-кодом:
<img src = "https://static.donationalerts.ru/uploads/qr/9599472/qr_28108597a3d6a629e476629d1ec785ff.png" height = "10%"><hr>
</div>
<div>
	<div>
<?php
	//выше донатики 
	//ниже вывод книг для всех
	$sql = mysqli_query($connection, "SELECT * FROM `books`");
	while ($record = mysqli_fetch_assoc($sql))
	{
		//выводим рефакторинговую книгу. тут и таблицы сразу
		$id_auth = $record['author'];
		$id = $record['id'];
		$name = $record['name'];
		$substance = $record['substance'];
		$text = $record['text'];
		$serch = array('##-##', '#*', '*#', '#-', '-#', '#_', '_#', '#/', '/#', '#!', '!#', '#=', '=#', '#+', 'r#', 'o#', 'g#', 'b#', 'p#', 'y#', '+#', '#-##', '##-#', '#-#', '#_#', '###', '#%#');
		$replase = array('<br>', '<b>', '</b>', '<s>', '</s>', '<u>', '</u>', '<em>', '</em>', '<i>', '</i>', '<tt>', '</tt>', '<font color = ', '"red">', '"orange">', '"green">', '"blue">', '"purpule">', '"yellow">', '</font>', '<p align = left>', '<p align = right>', '<p align = center>', '<p align = justify>', '</p>', '&emsp;');
		$out = str_replace($serch, $replase, $text); 
		?>
		<ui>
			<li>
			<img src="../src/<?=$record['img'];?>" width="150" height="150" alt = "ошибка" loading="lazy"><br>
			<strong><?= $name;?></strong><br>
			<?php $substanc = str_replace($serch, $replase, $substance);
				  echo $substanc;
			?><br>
			Авоор: <?= $record['author'];?><br>
			Теги: <?= $record['tag'];?> &emsp; 
			<form action = "./admin_tool/report.php" method = "POST">
				<input type = "hidden" name = "theme" value = "Жалоба на книгу">
				<input type = "hidden" name = "id_comment" value = " ">
				<input type = "hidden" name = "id_book" value = "<?=$id?>">
				<input type = "submit" name = "report_boock" value = "Пожаловаться">
			</form>
			<details>
				<summary>Полный текст</summary>
				<?php echo $out; ?>
			</details><hr></li>
		</ui>
	</div>
	<div>
		<details>
			<summary>Комментарии</summary>
			<div>
				<div>
				<?php if(!$banned) {?>
					<form method = "POST" action = "comment_editor">
						<input type = "hidden" name = "boock_id" value = "<?=$id?>">
						<input type = "submit" name = "add_comment" value = "Добавить комментарий">
					</form>
				</div>
				<div>
				<?php }
				$comment_sql = mysqli_query($connection, "SELECT * FROM `comments` WHERE `boock_id` LIKE '$id'");
				if(mysqli_num_rows($comment_sql) == '0')
				{
					echo "<br>Коментариев нет...";
				}
				else
				{
					while($record_comment = mysqli_fetch_assoc($comment_sql))
					{
						$comm = $record_comment['comment'];
						$date_comment = $record_comment['date'];
						$comment_author = $record_comment['user_name']; ?>
						Оставлен: <?=$date_comment;?>, &emsp;Пользователем: <?=$comment_author?><br>
						<?=$comm?>
							<form action = "./admin_tool/report.php" method = "POST">
								<input type = "hidden" name = "theme" value = "Жалоба на комментарий">
								<input type = "hidden" name = "id_comment" value = "<?=$record_comment['id']?>">
								<input type = "hidden" name = "id_book" value = " ">
								<input type = "submit" name = "report_boock" value = "Пожаловаться">
							</form>
						<?php
						if($role == 1 || $role == 3){
						?>
						<div>
							<form method = "POST" action = "./admin_tool/comment_dellit.php">
								<input type = "hidden" name = "id_comment" value = "<?=$record_comment['id']?>">
								<input type = "submit" name = "dellit" value = "Удалить">
							</form><br>
						</div>
						<?php }
					}
				}?>
				</div>
			</div>
		</details><hr>
	</div>
<?php
	if ($role == 1 || $role == 3){
	if ($role == 3)
	{
		//запроси что кому нужно. 3 -- хост 1 -- петух на админе. ниже расписано что-кто должен видеть. как ты будешь это верстать, я в душах не ебу :3
		/*если будет делать нехуй, верстай прям тут. главное <?= [хуй]?> не трогай, это логика для вывода всякой всячины*/
		$sql_auth = mysqli_fetch_assoc(mysqli_query($connection, "SELECT id FROM `users` WHERE `login` LIKE '$id_auth'"));
	}?>
	<div>
		<details>
			<summary>Информация о книге</summary>
			<?php if ($role == 3 && $sql_auth){ ?>
			<br>ID автора: <?=$sql_auth['id']; }?>
			<br>ID книги: #<?=$id;?>
			<form action = "./admin_tool/admin.php" method = "GET">
				<input type = "hidden" name = "author_id" value = "<?=$id_auth?>">
				<input type = "hidden" name = "boock_id" value = "<?=$id?>">
				Удалить книгу: <input type = "submit" value = "Удалить" name = "del_book"><br>
				<?php if ($role == 3 && $sql_auth){?>
				Заблокировать автора: <input type = "submit" value = "Заблокировать" name = "ban"><?php }?>
			</form>
		</details><hr>
	</div>
</div>
<?php
}}
?>