<?php 
include_once 'core/ini.php';
include_once 'include/overall/overallheader.php'; 
page_protect();

$add_error = array();

if(!isset($_POST['add'])){
	$list_id = $_GET['list_id'];
}else{
	$today_date = date("Y-m-d", strtotime("now"));//get today's date
	$task_name = clean($_POST['task_name']); 
	$list_id = $_POST['list_id'];
	
	if(empty($task_name)){
		$add_error[] = "Please enter a task name";
	}else{
		if(task_exists($list_id, $task_name)){
			$add_error[] = "This task already exists";
		}else{
			mysql_query("INSERT INTO todo_list_task(todo_list_id,task_name,create_date) VALUES('$list_id','$task_name','$today_date')");
		}
	}
}

?>

<html>
<div class = "main_form">
<h2>Add a Task To To-Do List</h2>
<?php
	if(isset($_POST['add'])){
		if(empty($add_error)){
			echo "You have add task: $task_name";
		}else{
			echo output_errors($add_error);
		}
	}
?>
<form action= "todo_list_create2.php" method = "post">
	<ul>
		<input type="hidden" name= "list_id"  value = "<?=$list_id?>" ></li>
		
		<li>Task Name: <br> <input type="text" name="task_name"></li>
	
		<li><input type="submit" name= "add" value= "Add"></li>
    </ul>
</form>
</div>

</html>



<?php
include_once 'include/overall/overallfooter.php'; 
?>