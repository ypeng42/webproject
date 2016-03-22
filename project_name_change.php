<?php 
include_once 'core/ini.php';
include_once 'include/overall/overallheader.php'; 
admin_protect();

$project_id = $_GET['project_id'];

if(isset($_POST['submit'])){
	$new_name = $_POST['new_name'];
	$id = $_POST['id'];
	mysql_query("UPDATE project SET project_name = '$new_name' WHERE project_id = '$id'");
	header("Location: project_progress.php");//automatically go back
}
?>

<html>

<div class = "main_form">
<h2>Change Project Name</h2>
<form action="project_name_change.php" method="post" >
	<ul>
	<li>Change the Name To: <input type="text" name = "new_name"></li>
	<input type = "hidden" name = "id" value = "<?=$project_id?>"/>
	<li><input type="submit" name= "submit" value= "Submit"></li>
	</ul>
</form>
</div>
</html>


<?php
include_once 'include/overall/overallfooter.php'; 
?>