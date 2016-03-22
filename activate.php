<?php
include 'core/ini.php';
$username = clean($_GET['username']);
$code = clean($_GET['code']);

if($username&&$code){
	$sql = mysql_query("SELECT * FROM user WHERE username = '$username' AND randomnum = '$code'");
	if(mysql_num_rows($sql)==1){
		mysql_query("UPDATE user SET enroll='1'WHERE username='$username'");//activate the account
		die("Your account is successfully activated!");
	}
} 
?>