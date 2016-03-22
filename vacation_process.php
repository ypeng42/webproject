<?php
include_once 'core/ini.php';
include_once 'include/overall/overallheader.php'; 
admin_protect();

$sql = "SELECT * FROM vacation WHERE checked = 0 AND void = 0";//select all the unchecked and uncancelled request
$result = mysql_query($sql);
$row = mysql_num_rows($result);

echo "<ul id='vacation_process'>";
if($row>0){
	while($row = mysql_fetch_assoc($result)){//print out requests
			$v_id = $row['id'];
			$pay_day = $row['number_of_paydays'];
			$unpay_day = $row['number_of_unpaydays'];
			$day = $pay_day+$unpay_day;
			$start_date = $row['start_date'];
			$end_date = $row['end_date'];
			
			$user_id = $row['employee_id'];
			$data = user_data($user_id);
	
			$first_name = $data['firstName'];
			$mid_name = $data['midName'];
			$last_name = $data['lastName'];
			echo "<li>Request from: $first_name $mid_name $last_name | Number of day: $day | From $start_date to $end_date  
			<a href='vacation_process_more.php?u_id=$user_id&v_id=$v_id&'> view </a></li>";
	}
} else if($row == 0){
	echo "<h2> There is no vacation request to be processed.</h2>";
}
echo "</ul>";
include_once 'include/overall/overallfooter.php'; 
?>