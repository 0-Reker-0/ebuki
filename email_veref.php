<?php
	include ".\include\db.php";
	session_start();
	//хуйня которую закинет в бд
	$v_vord = $_SESSION['v_vord'];
	$post_login = $_SESSION['login'];
	$post_pass = $_SESSION['password'];
	$post_email = $_SESSION['email'];
	//этот эхо надо поменять, как только убеждусь что мыло работает
	echo 'По причине пошёл нахуй вамп, пхп, и этот мир ёбаный, ай да хуй с ним.<br>код подтвердения: '.$v_vord;
	if (isset($_POST['fin']))
	{
		if ($_POST['ver_vord'] == $v_vord)
		{
			//срём в бд
			$sql = mysqli_prepare($connection, "INSERT INTO users (`id`, `login`, `password`, `email`, `role`) VALUES (NULL, ?, ?, ?, 2)");
			mysqli_stmt_bind_param($sql, 'sss', $post_login, $post_pass, $post_email);
			$result = mysqli_stmt_execute($sql);
			if ($result != true)
			{
				echo 'Ошибка! Запись не создана!<hr>';
			}
			elseif ($result == true)
			{
				//ежели в бд нарало нормально
				session_destroy();
				echo '<br>Регистрация успешна!';
				session_start();
				$_SESSION['login'] = $post_login;
				$_SESSION['role'] = 2;
				setcookie("auth[name]", $post_login, time()+60*60*60*24);
				setcookie("auth[role]", $role['role'], time()+60*60*60*24);
				header("Location: ./books/index.php");
			}
		}
		else
		{
			echo 'Код введён неверно!';
		}
	}
?>

<form method = "POST">
	<input type = "text" required placeholder = "Введите код из письма" name = ver_vord>
	<input type = "submit" value = "Подтвердить" name = fin>
</form>