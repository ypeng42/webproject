<?php 
include_once 'core/ini.php';
include_once 'include/overall/overallheader.php'; 
page_protect();

$project_id = $_GET['project_id'];
$project_name = get_project_name($project_id);
$description = get_project_description($project_name);

echo "<div class='main_form'>";
echo "Project ID: $project_id<br>";
echo "Project Name: $project_name<br>";
echo "Description: $description<br>";

$e_id = $_SESSION['user_id'];// employee id
$date = date('Y-m-d',strtotime('now'));// get the date of submission

if(isset($_POST['submit'])){
	$id = $_POST['id'];
	$message = clean($_POST['message']); //get the update message
	if(!empty($message)){
		mysql_query("INSERT INTO project_message (project_id,message,employeeID,submit_date) VALUES ('$id','$message','$e_id','$date')");
	}
	header("Location: user_project_info.php");//automatically go back
}
?>

<html>
<form action= "user_project_message.php" method = "post">
 	Update Message: <input type="text" name="message"><br>
 	<input type = "hidden" name = "id" value = "<?=$project_id?>"/>
 	<input type="submit" name= "submit" value= "Submit">
</form>
</div>
</html>
<?php
include_once 'include/overall/overallfooter.php'; 
?>