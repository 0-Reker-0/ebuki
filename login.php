<?php
	include ".\include\db.php";
	echo '<hr>Авторизация<hr>';
	$data = $_POST;
	if (isset($data['auth']))
	{
		//всякие переменные с подввязанными данными из форм
		$error = array();
		$h1_pass = trim($data['password']);
		$h1_cw = $data['c-word'];
		$h_pass = $h1_cw.$h1_pass;
		$post_login = htmlspecialchars($data['login']);
		//хуйня из бд
		$get_login = mysqli_num_rows(mysqli_query($connection, "SELECT * FROM `users` WHERE `login` LIKE '$post_login'"));
		if ($get_login == 1)
		{
			//тута ежели еблан регнут, проверяем парольчик
			$sql = mysqli_query($connection, "SELECT `password` FROM `users` WHERE `login` = '$post_login'");
			$pas = mysqli_fetch_array($sql, MYSQLI_ASSOC);
			$post_pass = $pas['password'];
			$get_pass = password_verify($h_pass, $post_pass);
			if (!$get_pass)
			{
				$error[] = 'Пароль или кодовое слово введены неверно!';
			}
			else
			{
				//куки, хуяюки, сессии. пошли все нахуй (на мейн)
				$sql_role = mysqli_query($connection, "SELECT `role` FROM `users` WHERE `login` = '$post_login'");
				$role = mysqli_fetch_assoc($sql_role);
				session_start();
				$_SESSION['login'] = $post_login;
				$_SESSION['role'] = $role['role'];
				setcookie("auth[name]", $post_login, time()+60*60*60*24);
				setcookie("auth[role]", $role['role'], time()+60*60*60*24);
				header("Location: ./books/index.php");
			}
		}
		else
		{
			//предложение пройти регистрацию. и так видно
			$error[] = 'Пользователя с таким логином не существует!<br>Можете зарегистрироваться нажав на кнопку:';?>
			<form action = 'registration.php'><input type = "submit" value = "Регистрация"></form>.
			<?php
		}
		if (! empty($error))
		{
			//типо как у хауд ага-да
			echo "<hr><div style*color: red;>".array_shift($error)."<hr>";
		}
	}
?>
<form action = "login.php" method = "POST">
	<input type = "text" required placeholder = "Введите логин" name = "login">
	<input type = "password" required placeholder = "Введите пароль" name = "password">
	<input type = "text" required placeholder = "Введите код-слово" name = "c-word">
	<br><br>
	<input type = "submit" value = "Авторизироваться" name = "auth">
</form>