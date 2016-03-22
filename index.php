<?php 
include_once 'core/ini.php';
include_once 'include/overall/overallheader.php'; 

if(logged_in() == true){
	header('Location: user_catch_up.php');
}
?>

<h1>Welcome to this site</h1>
<p>this is a paragraph</p>

<?php
include_once 'include/overall/overallfooter.php'; 
?>