<?php
include_once 'core/ini.php';

$error = array();
if(empty($_POST) == false){
	$username = $_POST['username'];
	$password = $_POST['password'];
	
	if(empty($username)||empty($password)){
		$error[] = 'You need to enter username and password';
	} else if(user_exists($username)==false){
		$error[] = 'user not exist';
	} else if(user_active($username)==false){
		$error[] = 'This account is not activated';
	} else{
		$login = login($username,$password);
		if($login == false){
			$error[] = 'Wrong username or password';
		}else{
			$_SESSION['user_id'] = get_user_id("$username");
			$_SESSION['admin'] = is_admin($username);
			header('Location: index.php');
		}
	}
}
include 'include/overall/overallheader.php';
if(empty($error) == false){
	echo '<h2> Problem when log in:</h2>';
	echo output_errors($error);
}
include_once 'include/overall/overallfooter.php';
?>