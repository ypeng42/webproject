<?php
//No enter without logged in
function page_protect(){
	if(logged_in() == false){
		header('Location: index.php');
	}
}

function admin_protect(){
	if(logged_in() == false){
		header('Location: index.php');
	}else{
		if(!$_SESSION['admin']){
			header('Location: index.php');
		}
	}
}
//prevent sql injection
function clean($data){
	return mysql_real_escape_string($data);
}

//output error in a good looking way
function output_errors($error){
	$output = array();
	foreach($error as $er){
		$output[] = '<li>'.$er.'</li>';	
	}
	return '<ul>'.implode('',$output).'</ul>';
}

//check whether is weekend
function is_weekend($date){
	return (date("N", strtotime($date)) >= 6);
}

//check whether is holiday
function is_holiday($date){
	$sql = "SELECT * FROM holiday WHERE holiday_date = '$date'";
	$result = mysql_query($sql);
	if(mysql_num_rows($result)>0){
		return true;
	}else{
		return false;
	}
}

//check if array contain certain thing
function array_contain($arr,$thing){
	$contain = false;
	foreach($arr as $item){
		if($item==$thing){
			$contain = true;	
		}
	}
	return $contain;
}

//returns an array contains all date when update happened
function get_all_update_date($e_id){
	$arr = array();
	if(!is_admin_eid($e_id)){
		$sql = "SELECT submit_date FROM project_message WHERE project_id IN 
		(SELECT project_id FROM project_assign WHERE employeeID = '$e_id') UNION 
		SELECT complete_date FROM todo_list_complete WHERE task_id IN 
		(SELECT task_id FROM todo_list_task WHERE todo_list_id IN 
		(SELECT todo_list_id FROM todo_list_create WHERE project_id IN 
		(SELECT project_id FROM project_assign WHERE employeeID = '$e_id'))) 
		UNION SELECT create_date FROM todo_list_task WHERE task_id IN 
		(SELECT task_id FROM todo_list_task WHERE todo_list_id IN 
		(SELECT todo_list_id FROM todo_list_create WHERE project_id IN 
		(SELECT project_id FROM project_assign WHERE employeeID = '$e_id'))) ORDER BY submit_date DESC";
	}else{//if is admin, get access to all projects
		$sql = "SELECT submit_date FROM project_message UNION SELECT complete_date FROM todo_list_complete UNION SELECT create_date FROM todo_list_task
		ORDER BY submit_date DESC";
	}	

	$result = mysql_query($sql);

	if(mysql_num_rows($result)==0){
		return false;
	}else{
		while($row = mysql_fetch_array($result)){
			array_push($arr,$row[0]);
		}
		return $arr;
	}
	/*
	$sql = "SELECT project_id FROM project_assign WHERE employeeID = '$e_id'";
	$result = mysql_query($sql);
	$arr = array();
	if(mysql_num_rows($result)==0){
		return false;
	}else{
		while($row = mysql_fetch_assoc($result)){
			$project_id = $row['project_id'];
			$m_result = mysql_query("SELECT submit_date FROM project_message WHERE project_id = '$project_id'");
			if(mysql_num_rows($m_result)!=0){
				while($m_row = mysql_fetch_array($m_result)){//add message date
					array_push($arr,$m_row[0]);
				}
			}
			
			$result1 = mysql_query("SELECT todo_list_id FROM todo_list_create WHERE project_id = '$project_id'");
			if(mysql_num_rows($result1)!=0){
				while($row1 = mysql_fetch_assoc($result1)){
					$todo_list_id = $row1['todo_list_id'];//list_id
					$result2 = mysql_query("SELECT * FROM todo_list_task WHERE todo_list_id = '$todo_list_id'");
					if(mysql_num_rows($result2)!=0){
						while($row2 = mysql_fetch_assoc($result2)){
							$task_id = $row2['task_id'];//task_id
							if(!array_contain($arr,$row2['create_date'])){//add create date
								array_push($arr,$row2['create_date']);
							}
							
							if(task_complete($task_id)){//add complete date
								$t_date = task_complete_date($task_id);
								if(!array_contain($arr,$t_date)){
									array_push($arr,$t_date);
								}
							}
						}
					}
				}
			}
		}
		
		
		if(!empty($arr)){
			return $arr;
		}else{
			ksort($arr);
			return false;
		}
	}
	*/
}

function get_current_date_index($e_id,$c_date){
	$arr = get_all_update_date($e_id);
	$index = -0.5;
	if(!is_bool($arr)){
		$len = count($arr);
		for($i =0; $i<$len;$i++){
			if($c_date<=$arr[$i]){
				$index++;
			}
		}
	}
	return $index;
}
?>