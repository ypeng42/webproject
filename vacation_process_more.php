<?php
include_once 'core/ini.php';
include_once 'include/overall/overallheader.php'; 
admin_protect();

$admin_id = $_SESSION['user_id'];
$u_id = clean($_GET['u_id']);
$v_id = clean($_GET['v_id']);

$u_data = user_data($u_id);
$v_data = vacation_data($v_id);
$start_date = $v_data['start_date'];
$end_date = $v_data['end_date'];
$pay_day = $v_data['number_of_paydays'];
$unpay_day = $v_data['number_of_unpaydays'];
$message = $v_data['employee_message'];


echo 'Employee Name: '.$u_data['firstName'].' '.$u_data['midName'].' '.$u_data['lastName'].'<br>';
echo "Days Avaliable: ".$u_data['vacation_day'].'<br>';
echo "Paid Day Request: $pay_day<br>";
echo "Unpaid Day Request: $unpay_day<br>";
echo "From $start_date to $end_date<br><br>";
echo "Employee Message: $message";

if(isset($_POST['agree'])){//update
	$manage_message = clean($_POST['manage_message']);
	$u_id = clean($_POST['u_id']);
	$v_id = clean($_POST['v_id']);

	$u_data = user_data($u_id);
	$day_avaliable = $u_data['vacation_day'];
	
	$v_data = vacation_data($v_id);
	$pay_day = $v_data['number_of_paydays'];
	
	mysql_query("UPDATE vacation SET checked = 1, is_approved = 1, approved_by_id = '$admin_id', management_message = '$manage_message' WHERE id = '$v_id'");
	$new_day = $day_avaliable - $pay_day;
	mysql_query("UPDATE user SET vacation_day = '$new_day' WHERE employeeID = '$u_id'");

	header("Location: vacation_process.php");	//automatically go back

}elseif(isset($_POST['deny'])){
	$manage_message = clean($_POST['manage_message']);
	$v_id = clean($_POST['v_id']);
	mysql_query("UPDATE vacation SET checked = 1, is_approved = 0, management_message = '$manage_message' WHERE id = '$v_id'");

	header("Location: vacation_process.php");	//automatically go back
}
?>

<html>

<form action= "vacation_process_more.php" method = "post">
	<input type = "hidden" name = "u_id" value = "<?=$u_id?>"/>
	<input type = "hidden" name = "v_id" value = "<?=$v_id?>"/>
	Enter Message: <input type = "text" name = "manage_message"><br>
 	<input type="submit" name="agree" value = "Agree">
 	<input type="submit" name="deny"  value = "Deny">
 	
</form>

</html>
<?php
include_once 'include/overall/overallfooter.php'; 
?>