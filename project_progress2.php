<?php 
include_once 'core/ini.php';
include_once 'include/overall/overallheader.php'; 
admin_protect();

$project_id = clean($_GET['project_id']);
$project_name = get_project_name($project_id);
$description = get_project_description($project_name);
echo "<div class = 'main_form'>Project No.$project_id: $project_name<br>";//get name and description
echo "Description: $description<br><br>";

$sql = "SELECT * FROM project_message WHERE project_id = '$project_id'";
$result = mysql_query($sql);//get information

echo "<table border = '1'>";
echo "<tr>";
echo "<td> Employee Name </td>";
echo "<td> Update Message</td>";
echo "<td> Date </td>";
echo "</tr>";
while($row = mysql_fetch_assoc($result)){
	echo "<tr>";
	$message = $row['message'];
	$date = $row['submit_date'];
	$e_id = $row['employeeID'];
	
	$data = user_data($e_id);
	$first_name = $data['firstName'];
	$mid_name = $data['midName'];
	$last_name = $data['lastName'];

	echo "<td> $first_name $mid_name $last_name</td>";//put information in the table
	echo "<td> $message </td>";
	echo "<td> $date </td>";
	echo "</tr>";
}
echo "</table><br>";
echo "<a href='project_progress.php'> Go back </a></div>";
include_once 'include/overall/overallfooter.php'; 
?>