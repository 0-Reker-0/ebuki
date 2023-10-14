<?php
	include "..\include\db.php";
	session_start();
	$author = $_SESSION['login'];
	$data = $_POST;
	if (isset($data['submit']) && $_FILES['img']['name'] != '')
	{
		//я думаю, что и так можно понять, что все эти ебобаные переменные обозначают
		$error = array();
		if (!isset($data['v_author'])) {$v_author = $author;}
		else {$v_author = 'Безымянный автор';}
		$substance = htmlspecialchars($data['substance']);
		$name = htmlspecialchars($data['name']);
		$tag = htmlspecialchars($data['tag']);
		$text = htmlspecialchars($data['text']);
		//пикчи
		$img = $_FILES['img']['name'];
		//где они хранятся
		$patch_img = "../src/".$img;
		$move = move_uploaded_file($_FILES['img']['tmp_name'], $patch_img);
		//проверочки, ок-да
		$type = $_FILES['img']['type'];
		var_dump($type);
		if (!($type == 'image/jpeg' or $type == 'image/png')){
			$error[] = 'Фаил не является изображением!';
		}
		if (!$move){
			$error[] = 'Фаил на сервер не загружен!';
		}
		if (strlen($name) > 80)
		{
			$error[] = 'Название слишком длинное!<br>Максимум 80 символов!';
		}
		if (strlen($tag) > 225)
		{
			$error[] = 'Слишком много тегов, или их длина слишком велика!<br>Максимум 225 символов!';
		}
		if (strlen($substance) > 300)
		{
			$error[] = 'Описание слишком длинное!<br>Максимум 300 символов!';
		}
		if (empty($error))
		{
			//покуда всё заебись, пидорим всю хуйню в бд
			$sql = mysqli_prepare($connection, "INSERT INTO `books` (`id`, `substance`, `name`, `tag`, `author`, `img`, `text`) VALUES (NULL, ?, ?, ?, ?, ?, ?);");
			mysqli_stmt_bind_param($sql, 'ssssss', $substance, $name, $tag, $v_author, $img, $text);
			$result = mysqli_stmt_execute($sql);
			if ($result){
				header("Location: index.php");
			}
			else{
				echo '<hr>Ошибка отправки! Книга не сохранена.<hr>';
			}
		}
		else{
			echo "<hr>".array_shift($error)."<hr>";
		}
	}
?>
<form id = "boock_add" method = "POST" enctype = "multipart/form-data">
	<input type = "text" required placeholder = "Введите название" name = "name">
	<input type = "text" required placeholder = "Введите теги" name = "tag"><br>
	Хотите остаться анонимным автором? <input type= "checkbox" name = "v_author">
	<br>
	<input type = "file" name = "img">
	Описание к книге:
	<p><textarea style="width:360px; height:150px;" name = "substance" required placeholder = "Описание..."></textarea></p>
	Текст книги:
	<p><textarea style="width:550px; height:350px;" name = "text" required placeholder = "Содержание..."></textarea></p>
	<input type = "submit" name = "submit" value = "Загрузить">
</form>
<table border = "1" cellpadding = "5">
	<caption>Таблица форматирования</caption>
	<tr>
		<td>Шрифты</td>
		<td>Выравнивание</td>
		<td>Дополнительно</td>
		<td>Цвета (открывать: #+ (тег цвета)закрывающий: +#</td>
	</tr>
	<tr>
		<td>Италик: #! закрывающий !#</td>
		<td>По левому: #-## закрывающий ###</td>
		<td>Отступ: #%#</td>
		<td>Красный: r#</td>
	</tr>
	<tr>
		<td>Жирный: #* закрывающий *#</td>
		<td>По правому: ##-# закрывающий ###</td>
		<td>Подчёркивание: #_ закрывающий _#</td>
		<td>Оранжевый: o#</td>
	</tr>
	<tr>
		<td>Курсив: #/ закрывающий /#</td>
		<td>По центру: #-# закрывающий ###</td>
		<td>Разрыв строки: ##-##</td>
		<td>Синий: b#</td>
	</tr>
	<tr>
		<td>Моно: #= закрывающий =#</td>
		<td>Общее: #_# закрывающий ###</td>
		<td>(ПУСТО)</td>
		<td>Жёлтый: y#</td>
	</tr>
	<tr>
		<td>(ПУСТО)</td>
		<td>(ПУСТО)</td>
		<td>(ПУСТО)</td>
		<td>Зелёный: g#</td>
	</tr>
	<tr>
		<td>(ПУСТО)</td>
		<td>(ПУСТО)</td>
		<td>(ПУСТО)</td>
		<td>Пурпурный: p#</td>
	</tr>
</table>