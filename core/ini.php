<?php
session_start();
ob_start();
require_once 'core/database/connect.php';	
require_once 'core/function/user_function.php';	
require_once 'core/function/general_function.php';	
date_default_timezone_set('America/Los_Angeles');//set the time zone

if(logged_in()){
	$user_id = $_SESSION['user_id'];
	$user_data = user_data($user_id);
}

?>