<?php
//function return all user's info in an assoc array
function user_data($user_id){
	$user_id = clean($user_id);
	$data = array();
	$sql = "SELECT * FROM user WHERE employeeID = '$user_id'";
	$data = mysql_fetch_assoc(mysql_query($sql));
	return $data;
}

//check if a user is already been add to a project
function user_added($user_id,$project_id){
	$sql = "SELECT * FROM project_assign WHERE employeeID = '$user_id' AND project_id = '$project_id'";
	$result = mysql_query($sql);
	if(mysql_num_rows($result)==0){
		return false;
	}else{
		return true;
	}
}

//function return vacation data
function vacation_data($id){
	$id = (int)$id;
	$data = array();
	$sql = "SELECT * FROM vacation WHERE id = '$id'";
	$data = mysql_fetch_assoc(mysql_query($sql));
	return $data;	
}

//check if the user logged in
function logged_in(){
	return (isset($_SESSION['user_id']));
}

//get username from first and last name
function get_eid_from_name($first,$mid,$last){
	$first = clean($first);
	$mid = clean($mid);
	$last = clean($last);
	$sql = "SELECT employeeID FROM user WHERE firstName = '$first' AND midName = '$mid' AND lastName = '$last'";
	$result = mysql_query($sql);
	if(mysql_num_rows($result)==0){
		return false;
	}else{
		return (mysql_result($result,0));
	}
	
}

//check if the user have permission to create a list; admin can access any project
function creat_list_permission($e_id,$project_id){
	if(!$_SESSION['admin']){
	$project_id = clean($project_id);
	$sql = "SELECT COUNT(*) FROM project_assign WHERE project_id = '$project_id' AND employeeID = '$e_id'";
	$result = mysql_query($sql);
	
	$final = mysql_result($result,0) > 0;
	}else{
		$final = true;
	}
	return $final;
}

//check if to-do list exists
function list_exists($id){
	$id = clean($id);
	$sql = "SELECT COUNT(*) FROM todo_list_create WHERE todo_list_id = '$id'";
	$result = mysql_query($sql);
	return (mysql_result($result,0) == 1);
}

//check if task exists in to-do list
function task_exists($id,$name){
	$id = clean($id);
	$name = clean($name);
	$sql = "SELECT COUNT(*) FROM todo_list_task WHERE task_name = '$name' AND todo_list_id = '$id'";
	$result = mysql_query($sql);
	return (mysql_result($result,0) == 1);
}

//check if task is completed
function task_complete($id){
	$id = clean($id);
	$sql = "SELECT COUNT(*) FROM todo_list_complete WHERE task_id = '$id'";
	$result = mysql_query($sql);
	return (mysql_result($result,0) == 1);
}

//get the complete date of the task
function task_complete_date($id){
	$id = clean($id);
	$sql = "SELECT complete_date FROM todo_list_complete WHERE task_id = '$id'";
	$result = mysql_query($sql);
	if(mysql_num_rows($result)>0){
		return (mysql_result($result,0,'complete_date'));
	}else{
		return '0000-00-00';
	}
	
}

//check whether list has any update
function list_has_update($list_id,$date){
	$check = false;
	$sql = "SELECT * FROM todo_list_task WHERE todo_list_id = '$list_id'";
	$result = mysql_query($sql);
	while($row = mysql_fetch_assoc($result)){
		$task_id = $row['task_id'];
		$create_date = $row['create_date'];
		if($create_date == $date){
			$check = true;
		}
		if(task_complete_date($task_id)==$date){
			$check = true;
		}
	}
	return $check;
}

//check if there is a todo-list for the project
function project_has_list($project_id){
	$project_id = clean($project_id);
	$sql = "SELECT COUNT(*) FROM todo_list_create WHERE project_id = '$project_id'";
	$result = mysql_query($sql);
	return (mysql_result($result,0) >0);
}

//check if project name exists
function project_exists($id){
	$id = clean($id);
	$sql = "SELECT COUNT(*) FROM project WHERE project_id = '$id'";
	$result = mysql_query($sql);
	return (mysql_result($result,0) == 1);
}

function get_project_name($id){
	$id = clean($id);
	$result = mysql_query("SELECT project_name FROM project WHERE project_id = '$id'");
	return (mysql_result($result, 0 ,'project_name'));
}

function get_project_description($name){
	$name = clean($name);
	$result = mysql_query("SELECT description FROM project WHERE project_name = '$name'");
	return (mysql_result($result, 0 ,'description'));
}

//check if the account exists
function user_exists($username){
	$username = clean($username);
	$sql = "SELECT COUNT(*) FROM user WHERE username = '$username'";
	$result = mysql_query($sql);
	return (mysql_result($result,0) == 1);
}

//check if the user is admin
function is_admin($username){
	$username = clean($username);
	$sql = "SELECT admin FROM user WHERE username = '$username'";
	$result = mysql_query($sql);
	return (mysql_result($result,0) == 1);
}

//check if the user is admin using e_id
function is_admin_eid($e_id){
	$e_id = clean($e_id);
	$sql = "SELECT admin FROM user WHERE employeeID = '$e_id'";
	$result = mysql_query($sql);
	return (mysql_result($result,0) == 1);
}

//check if the account is active
function user_active($username){
	$username = clean($username);
	$sql = "SELECT COUNT(*) FROM user WHERE username = '$username' AND enroll = 1";
	$result = mysql_query($sql);
	return (mysql_result($result,0) == 1);
}

//get the id of a user
function get_user_id($username){
	$username = clean($username);
	$sql = "SELECT employeeID FROM user WHERE username = '$username'";
	$result = mysql_query($sql);
	return mysql_result($result,0,'employeeID');
}

//check if username and password match
function login($username,$password){
	$username = clean($username);
	$password = clean($password);
	$sql = "SELECT COUNT(*) FROM user WHERE username = '$username' AND password = '$password'";
	$result = mysql_query($sql);
	return (mysql_result($result,0) == 1);
}

function password_update($username,$password){
	$username = clean($username);
	$password = clean($password);
	$sql = "UPDATE user SET password = '$password' WHERE username = '$username'";
	$result = mysql_query($sql);
}
?>