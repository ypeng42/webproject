<?php 
include_once 'core/ini.php';
page_protect();

if(logged_in()){
	$v_id = $_GET['v_id'];
	$e_id = $_SESSION['user_id'];
	mysql_query("UPDATE vacation SET void = 1 WHERE id = '$v_id'");//set void
	
	$sql = "SELECT * FROM vacation WHERE void=0 AND checked = 1 AND is_approved = 1 AND employee_id = '$e_id'";//get vacation day
	$result = mysql_query($sql);
	$day = 0;

	while($row = mysql_fetch_assoc($result)){
		$day = $day + $row['number_of_paydays'];
	}

	if($day>=10){
		mysql_query("UPDATE user SET vacation_day = 0 WHERE employeeID = '$e_id'");
	}else{
		$new_day = 10-$day;
		mysql_query("UPDATE user SET vacation_day = '$new_day' WHERE employeeID = '$e_id'");
}

header("Location: personal_info.php");
}
?>
		