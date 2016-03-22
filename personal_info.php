<?php 
include_once 'core/ini.php';
include_once 'include/overall/overallheader.php'; 
page_protect();
	
echo "<h1>Personal Information</h1>";
echo 'Name: '.$user_data['firstName'].' '.$user_data['midName'].' '.$user_data['lastName'].'<br>';
echo "Address: ".$user_data['address']."<br>";
echo "City: ".$user_data['city']."<br>";
echo "Postcode: ".$user_data['postcode']."<br>";
echo "Country: ".$user_data['country']."<br>";
echo "Salary: ".$user_data['salary']."<br>";
echo "Hour Salary: ".$user_data['hourSalary']."<br><br>";
//view vacation info
echo "<h4>Vacation Information:</h4>";
echo "Paid vacation day avaliable: ".$user_data['vacation_day']."<br><br>";

$e_id = $_SESSION['user_id'];
$result = mysql_query("SELECT*FROM vacation WHERE employee_id = '$e_id'");//get all the vacation request 

echo "<h4>Vacation Request History: </h4>";
if(mysql_num_rows($result)==0){
	echo "You have no vacation request<br>";
}else{
	while($row = mysql_fetch_assoc($result)){//while loop
		$v_id = $row['id'];
		$start_date = $row['start_date'];
		$end_date = $row['end_date'];
		$void = $row['void'];
		$manage_message = $row['management_message'];
		//check the status of the request
		if($row['checked']==1){
			if($row['is_approved']==1){
				$is_approved = "Approved";
			}else if ($row['is_approved']==0){
				$is_approved = "Deny";
			}
		}else{
			$is_approved = "In Process";
		}

		if($void==0){
			if($is_approved=="Deny"){
				echo "From $start_date to $end_date | Status: $is_approved |
				Manager Message: '$manage_message' <br>";
			}else{
				echo "From $start_date to $end_date | Status: $is_approved |
			Manager Message: '$manage_message' <a href = 'user_vacation_cancel.php?v_id=$v_id'> cancel </a><br>";
			}		
		}
	}//while loop
}
include_once 'include/overall/overallfooter.php'; 
?>