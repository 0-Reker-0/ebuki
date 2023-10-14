<?php
	//хуита если чипиздр авторизован. кстати, файловую систему не трогать!
	include ".\include\db.php";
	echo 'тут должен быть раздел с информацией. я считаю, это уже дело дизайнера.';
	if (isset($_COOKIE['auth']))
	{
		header("Location: ./books/index.php");
	}
?>
<form action = "registration.php">
	<input type = "submit" value = "Регистрация">
</form>
<form action = "login.php">
	<input type = "submit" value = "Вход">
</form>
<hr>Просмотр книг
<?php //вывод книг
	$sql = mysqli_query($connection, "SELECT * FROM `books`");
	while ($record = mysqli_fetch_assoc($sql))
	{
		//выводим рефакторинговую книгу. тут и таблицы сразу
		$id = $record['id'];
		$name = $record['name'];
		$substance = $record['substance'];
		$text = $record['text'];
		$serch = array('##-##', '#*', '*#', '#-', '-#', '#_', '_#', '#/', '/#', '#!', '!#', '#=', '=#', '#+', 'r#', 'o#', 'g#', 'b#', 'p#', 'y#', '+#', '#-##', '##-#', '#-#', '#_#', '###', '#%#');
		$replase = array('<br>', '<b>', '</b>', '<s>', '</s>', '<u>', '</u>', '<em>', '</em>', '<i>', '</i>', '<tt>', '</tt>', '<font color = ', '"red">', '"orange">', '"green">', '"blue">', '"purpule">', '"yellow">', '</font>', '<p align = left>', '<p align = right>', '<p align = center>', '<p align = justify>', '</p>', '&emsp;');
		$out = str_replace($serch, $replase, $text); ?>
		<ui>
			<li>
			<img src="src/<?=$record['img'];?>" width="150" height="150" alt = "ошибка" loading="lazy"><br>
			<strong><?= $name;?></strong><br>
			<?php $substanc = str_replace($serch, $replase, $substance);
				  echo $substanc;
			?><br>
			Авоор: <?= $record['author'];?><br>
			Теги: <?= $record['tag'];?><br>
			<details>
				<summary>Полный текст</summary>
				<?php echo $out; ?>
				</details><hr></li>
					<div>
					<details>
						<summary>Комментарии</summary>
						<div>
							<div>
							<?php
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
									<?=$comm?><br><br>
							<?php }} ?>
						</div>
					</details><hr>
				</div>
		</ui>
<?php
	}
?>