<?php
include_once 'core/ini.php';
include_once 'include/overall/overallheader.php'; 
page_protect();

if($_POST['submit']){
	$today_date = date("Y-m-d", strtotime("now"));//get basic info
	$arr = $_POST['task'];
	//get all completed task_id from project
	$project_id = $_POST['project_id'];
	echo "$project_id";
	$sql = "SELECT * FROM todo_list_complete INNER JOIN todo_list_task
			ON todo_list_task.task_id = todo_list_complete.task_id
			INNER JOIN todo_list_create
	        ON todo_list_create.todo_list_id = todo_list_task.todo_list_id
			WHERE todo_list_create.project_id = '$project_id'";
	$result = mysql_query($sql);
	while($row = mysql_fetch_assoc($result)){//check each completed task, if they are still being checked
		$task_id = $row['task_id'];
		
		if(task_complete_date($task_id)==$today_date){
			if(!in_array($task_id,$arr)){
				mysql_query("DELETE FROM todo_list_complete WHERE task_id = '$task_id'");
			}
		}
	}
	
	foreach($arr as $it){
		if(!task_complete($it)){//prevent double insertion
			mysql_query("INSERT INTO todo_list_complete (task_id, complete_date) VALUES ('$it','$today_date')");
		}
	}
	
	header("Location: user_catch_up.php");
}
include_once 'include/overall/overallfooter.php'; 
?>