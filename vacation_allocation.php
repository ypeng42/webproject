<?php
include_once 'core/ini.php';
include_once 'include/overall/overallheader.php'; 

if(logged_in() == false){
	header('Location: index.php');
}else{
	if(!$_SESSION['admin']){
		header('Location: index.php');
	}else{
		$sql = "UPDATE user SET vacation_day=10";
		mysql_query($sql);
		echo "Each employee has 10 vacation days<br>";
	}
}

include_once 'include/overall/overallfooter.php'; 
?>