<?php 
include_once 'core/ini.php';
include_once 'include/overall/overallheader.php'; 
admin_protect();
$create_error = array();

if(isset($_POST['create'])){
	$project_name = clean($_POST['name']); 
	$description = clean($_POST['description']); 
	
	if(empty($project_name)||empty($description)){
		$create_error[] = "Please enter all fields";
	}else{
		$sql = "INSERT INTO project(project_name,description) VALUES('$project_name','$description') ";
		mysql_query($sql);
		
		$sql = "SELECT project_id FROM project WHERE project_name = '$project_name' 
		AND description = '$description' ORDER BY project_id DESC LIMIT 1";
		$result = mysql_query($sql);
		$project_id = mysql_result($result,0,'project_id');
		header("Location: project_add_people.php?project_id=$project_id");
	}
}
?>
		
<html>
<div class = "main_form">
<h2>Create a Project </h2>
<?php
	if(isset($_POST['create'])){
		if(!empty($create_error)){
			echo output_errors($create_error);
		}
	}
?>
<form action= "create_project.php" method = "post">
	<ul>
		<li>Project Name: <br><input type="text" name="name"></li>
	
		<li>Project Description: <br> <input type="text" name="description"></li>
	
		<li><input type="submit" name= "create" value= "Create"></li>
    </ul>
</form>
</div>

</html>

<?php include_once 'include/overall/overallfooter.php'; ?>