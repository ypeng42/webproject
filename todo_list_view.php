<?php 
include_once 'core/ini.php';
include_once 'include/overall/overallheader.php'; 
page_protect();

//starts manipulating input value
if(isset($_POST['submit'])){
	$date = date('Y-m-d',strtotime('now'));// get the date of submission
	$arr = $_POST['task'];

	//get all completed task_id from project
	$project_id = $_POST['project_id'];
	$sql = "SELECT * FROM todo_list_complete INNER JOIN todo_list_task
			ON todo_list_task.task_id = todo_list_complete.task_id
			INNER JOIN todo_list_create
	        ON todo_list_create.todo_list_id = todo_list_task.todo_list_id
			WHERE todo_list_create.project_id = '$project_id'";
	$result = mysql_query($sql);
	while($row = mysql_fetch_assoc($result)){
		$task_id = $row['task_id'];
		//check if the task is in the new array!!!!!!!!!!!!!!!!!!!!if not delete
		if(!in_array($task_id,$arr)){
			mysql_query("DELETE FROM todo_list_complete WHERE task_id = '$task_id'");
		}
		
	}
	
	foreach($arr as $task){
		if(!task_complete($task)){//prevent double insertion
			$sql = "INSERT INTO todo_list_complete (task_id, complete_date) VALUES ('$task','$date')";
			mysql_query($sql);
		}
	}

	header("Location: user_project_info.php");
}
//end of processing value

//starts generating checkbox input form
$project_id = $_GET['project_id'];
if(project_has_list($project_id)){
		
	$sql = "SELECT * FROM todo_list_create WHERE project_id = '$project_id'";
	$result = mysql_query($sql);

	echo "<form action = 'todo_list_view.php' method = 'post'>";
	while($row = mysql_fetch_assoc($result)){
		$list_id = $row['todo_list_id'];
		$list_name = $row['todo_list_name'];
		
		echo "<div class = 'main_form'>";
		echo "To-Do List No.$list_id: $list_name  | 
			  <a href='todo_list_create2.php?list_id=$list_id'> Add more task</a><br>";
		echo "<ul>";
		$sql2 = "SELECT * FROM todo_list_task WHERE todo_list_id = '$list_id'";
		$result2 = mysql_query($sql2);
		while($row2 = mysql_fetch_assoc($result2)){
			$task_id = $row2['task_id'];
			$task_name = $row2['task_name'];
			
			if(task_complete($task_id)){
				$complete_date = task_complete_date($task_id);
				echo "<li><input type='checkbox' name = 'task[]' value = '$task_id' checked> $task_name | Completed on: $complete_date</li>";
			}else{
				echo "<li><input type='checkbox' name = 'task[]' value = '$task_id'> $task_name</li>";
			}//check if the task is already completed
			
		}
		echo "</ul></div>";
	}
	echo "<div class='main_form'>";
	echo "<input type = 'hidden' name = 'project_id' value = '$project_id'>";
	echo "<input type='submit' name='submit' value='submit'>";
	echo "</div>";
	echo "</form>";
}else{
	echo "<h2>This project has no todo list yet</h2>";
}
//end of generating the form

include_once 'include/overall/overallfooter.php'; 
?>