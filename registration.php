<?php
	include ".\include\db.php";
	echo '<hr>Регистрация<hr>';
	$data = $_POST;
	if (isset($data['registration']))
	{
		//ну тупа куча переменных из форм
		$error = array();
		$password = trim($data['pass']);
		$post_login = htmlspecialchars($data['login']);
		$post_email = htmlspecialchars($data['email']);
		$get_login = mysqli_query($connection, "SELECT * FROM `users` WHERE `login` LIKE '$post_login'");
		$get_email = mysqli_query($connection, "SELECT * FROM `users` WHERE `email` LIKE '$post_email'");
		//всякие проверки
		if (strlen($post_login) < 3){
			$error[] = 'Логин слишком кароткий! Минимальное значение в 3 символа.';
		}
		if (strlen($post_login) > 30){
			$error[] = 'Логин слишком длинный! Максимальное значение в 30 символов.';
		}
		if (strlen($password) < 8){
			$error[] = 'Пароль слишком кароткий! Минимальное значение в 8 символов.';
		}
		if (strlen($password) > 25){
			$error[] = 'Пароль слишком длинный! Максимальное значение в 25 символов.';
		}
		if ($data['pass2'] != $password){
			$error[] = 'Повторный пароль введён неверно!';
		}
		if (mysqli_num_rows($get_login) > 0){
			$error[] = 'Пользователь с таким логином существует!';
		}
		if (mysqli_num_rows($get_email) > 0){
			$error[] = 'Пользователь с таким email существует!';
		}
		if (empty($error))
		{
			//передаёт на следующую страничку данные, шоб не засирать бд вскими пидорасами
			session_start();
			$_SESSION['login'] = $post_login;
			$_SESSION['email'] = $post_email;
			$h1_cw = $data['c-word'];
			$h1_pass = $data['pass'];
			$h_pass = $h1_cw.$h1_pass;
			$_SESSION['password'] = password_hash($h_pass, PASSWORD_ARGON2I);
			$v_vord = mt_rand(100, 1000);
			$_SESSION['v_vord'] = $v_vord;
			//а это, хуесос на имейле
			$to = $post_email;
			$subject = 'Подтверждение';
			$message = 'Ваш код подтверждения: '.$v_vord;
			$headers = 'From: me@gmail.com' . "\r\n";
			//ежели заебись кидает на верефикацию мыла
			if(mail($to, $subject, $message, $headers))
			{
				header("Location: email_veref.php");
			}
			else
			{
				echo 'Код подтверждения не отправлен!';
			}
		}
		else
		{
			echo "<hr><div style*color: red;>".array_shift($error)."<hr>";
		}
	}
?>
<form method = "POST">
	<input type = "text" required placeholder = "Введите логин" name = "login">
	<input type = "password" required placeholder = "Введите пароль" name = "pass">
	<input type = "password" required placeholder = "Введите пароль повторно" name = "pass2">
	<input type = "text" required placeholder = "Введите код-слово" name = "c-word">
	<input type = "email" required placeholder = "Введите адрес электронной почты" name = "email">
	<br><br>
	<input type = "submit" value = "Регистрация" name = "registration">
</form>