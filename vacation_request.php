<?php 
include_once 'core/ini.php';
include_once 'include/overall/overallheader.php'; 

date_default_timezone_set('America/Los_Angeles');//set the time zone
echo "<h2>Please choose the date of your vacation request</h2><br>";

if (isset($_POST['submit'])){
	$e_id = $_SESSION['user_id'];

	$start_date = clean($_POST['start_date']); 
	$end_date = clean($_POST['end_date']); 
	$message = clean($_POST['message']); //employee's message

	$begin = strtotime($start_date);
	$finish = strtotime($end_date);
	$duration = ($finish-$begin)/60/60/24;//number of day
	
	for($i = $begin; $i<=$finish; $i = $i+86400){//loop through each date in the interval and check
		$thisDate = date('Y-m-d',$i);
		if(is_weekend($thisDate)||is_holiday($thisDate)){
			$duration = $duration-1;//weekend or holiday doesn't count
		}
	}

	$sql = "SELECT email FROM user WHERE admin=1";
	$result = mysql_query($sql);
	$email = mysql_result($result,0);//get the email address of the admin
	
	if($duration<0){
		echo "ERROR: Incorrect date entry<br>";
	}else if($duration==0){
		echo "You don't need to apply for this vacation<br>";
	}else if(is_weekend($end_date)||is_holiday($end_date)){
		echo "ERROR: Return to work day cannot be weekend or holiday<br>";
	}else{//if the input is valid
		$u_data = user_data($e_id);
		$days_available = $u_data['vacation_day'];
		$first_name = $u_data['firstName'];
		$last_name = $u_data['lastName'];
		
		if($duration<$days_available){
			$sql = "INSERT INTO vacation (employee_id,number_of_paydays,number_of_unpaydays,employee_message,start_date,end_date) VALUES ('$e_id','$duration',0,'$message',
			'$start_date','$end_date')";
			$result = mysql_query($sql);
			echo "You have $days_available days avaliable for paid vacation. You apply for a paid vacation of $duration days<br><br>";

			//send emial to the admin
			$server = "mail.pengmaomao.com";
			ini_set("SMTP",$server);
			$body = "Vacation Request: \n

			Name: $first_name $last_name
			Employee ID: $e_id
			From $start_date to $end_date
			Paid days: $duration
			Unpaid days: 0
			Message: $message";
	
			mail($email,"Vacation Request",$body,"Request from $e_id");
		}else{
			$unpay = $duration-$days_available;

			$sql = "INSERT INTO vacation (employee_id,number_of_paydays,number_of_unpaydays,employee_message,start_date,end_date) VALUES ('$e_id','$days_available','$unpay','$message','$start_date','$end_date')";
			$result = mysql_query($sql);
			echo "You have $days_available days avaliable for paid vacation. You apply for $days_available days of paid vacation, and
			$unpay days of unpaid days of vavcation<br><br>";
			//send email to the admin
			
			$server = "mail.pengmaomao.com";
			ini_set("SMTP",$server);
			$body = "Vacation Request: \n

			Name: $first_name $last_name
			Employee ID: $e_id
			From $start_date to $end_date
			Paid days: $days_available
			Unpaid days: $unpay
			Message: $message";
			//to send email
			mail($email,"Vacation Request",$body,"Request from $e_id");
		}
	}
}
?>
		
<html>
<form action= "vacation_request.php" method = "post">
	<ul>
 	<li>Start Date: <br> <input type="date" name="start_date"></li>

 	<li>Return to work on: <br>  <input type="date" name="end_date"></li>
   
 	<li>Additional Message: <br> <input type="text" name="message"></li>
  
 	<li><input type="submit" name= "submit" value= "Submit"></li>
    </ul>
</form>
</html>
<?php include_once 'include/overall/overallfooter.php'; ?>