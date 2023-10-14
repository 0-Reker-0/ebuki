<?php
	//хуйло чтоб бд робило. папку оставлять в той-же директории. и впринцепе файловую систему не трогать
	$connection = mysqli_connect('127.0.0.1', 'root', '', 'pms');
	if ($connection == false)
	{
		echo 'К БД подключиться не удалось!';
		echo mysqli_connect_error();
		exit();
	}
?>