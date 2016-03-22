<?php 
include_once 'core/ini.php';
include_once 'include/overall/overallheader.php'; 
page_protect();

$create_error = array();
$project_id = clean($_GET['project_id']);
$e_id = $_SESSION['user_id'];
if(isset($_POST['create'])){
	$list_name = clean($_POST['list_name']); 
	$project_id = clean($_POST['project_id']);
	if(empty($list_name)){
		$create_error[] = "Please enter a name";
	}else if(!creat_list_permission($e_id,$project_id)){
		$create_error[] = "You are not allowed to create a list for this project";
	}else{
		if(project_exists($project_id)==false){
			$create_error[] = "Project doesn't exist";
		}else{
			$sql = "INSERT INTO todo_list_create(todo_list_name,project_id,employeeID) VALUES('$list_name','$project_id','$e_id') ";
			$result = mysql_query($sql);
			
			//get list id
			$sql = "SELECT todo_list_id FROM todo_list_create WHERE project_id = '$project_id' 
			AND todo_list_name = '$list_name' AND employeeID = '$e_id' ORDER BY todo_list_id DESC limit 1";
			$result = mysql_query($sql);
			$list_id = mysql_result($result,0,'todo_list_id');
			
			header("Location: todo_list_create2.php?list_id= $list_id");
		}
	}
	
}

?>

<html>
<div class = "main_form">
<h2>Create a To-Do List </h2>
<?php
if(isset($_POST['create'])){
	if(!empty($create_error)){
		echo output_errors($create_error);
	}
}
?>
<form action= "todo_list_create1.php" method = "post">
	<ul>
		<li>To-Do List Name: <br><input type="text" name="list_name"></li>
		
		<input type = "hidden" name = "project_id" value = "<?=$project_id?>">
		
		<li><input type="submit" name= "create" value= "Create"></li>
    </ul>
</form>
</div>
</html>

<?php
include_once 'include/overall/overallfooter.php'; 
?>