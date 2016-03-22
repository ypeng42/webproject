<?php 
include_once 'core/ini.php';
include_once 'include/overall/overallheader.php'; 
page_protect();
?>

<div class = "update">
<form action = "user_catch_up.php" method = "post">
	<ul>
		<li> Enter a date to check: <input type="date" name="check_date">     <input type="submit" name= "go" value= "Go"></li>	
	</ul>
</form>
</div>

<?php  
	$e_id = $_SESSION['user_id'];
	
	$date_arr = get_all_update_date($e_id);//array of dates when update happened
	$c_index = -1;//indicates which index of the date_array to use
	if(!is_bool($date_arr)){
		$max_length = count($date_arr);//length of date_arr
	}else{
		$max_length = 0;
	}
	
	if(!isset($_GET['incre'])&&!isset($_GET['decre'])){//no button is clicked
		if(!isset($_POST['go'])){//"go to date" button is not pushed
			$c_index = -1;
		}else{
			$c_index = get_current_date_index($e_id,clean($_POST['check_date']));
		}
	}else if(isset($_GET['incre'])){	
		$incre = $_GET['incre'];
		$c_index = floor($incre+1);
		if($c_index>($max_length-1)){
			$c_index = $max_length-1;//index can't exceed length of array
		}
		
	}else if(isset($_GET['decre'])){	
		$decre = $_GET['decre'];
		$c_index = ceil($decre-1);

		if($c_index<0){
			$c_index = 0;//index can't less that 0;
		}
	}
	
	if(!isset($_GET['incre'])&&!isset($_GET['decre'])){
		$today_date = date("Y-m-d", strtotime("now"));//get today's date
	}else{//when button is clicked
		if(!is_bool($date_arr)){
			$today_date = $date_arr[$c_index];
		}else{
			$today_date = date("Y-m-d", strtotime("now"));//get today's date
		}
	}
	
	//create 2 buttons, pass the value of current index
	echo "<a href= user_catch_up.php?incre=$c_index><div class= right_triangle></div></a>";
	echo "<a href= user_catch_up.php?decre=$c_index><div class= left_triangle></div></a>";
	
	$new_task_exist = 0;  //this is used to check whether there is new task created.
	
	
	if(!isset($_POST['go'])){//show today's info
		echo "<div class = 'update'>";
		echo "<h2>Catch up on $today_date</h2></div>";	

//--------------------------------------------------------------------------------------------------------------------------------------
//------------------------------------The following will be the same in the else clause---------------------------------------------------
			
			if(!$_SESSION['admin']){
				$sql = "SELECT * FROM project_assign WHERE employeeID = '$e_id'";
			}else{
				$sql = "SELECT *FROM project";
			}
			$result = mysql_query($sql);
			
			if(mysql_num_rows($result) == 0){
				echo "You don't have any project yet";
			}else{
				while($row = mysql_fetch_assoc($result)){//1st loop go through each project
					echo "<div class = 'main_form'>";
					$new_task_exist = 0; //set it back to 0
					$project_id = $row['project_id'];
					$sql2 = "SELECT * FROM project_message WHERE project_id = '$project_id' AND submit_date = '$today_date'";
					$result2 = mysql_query($sql2);
					
					$project_name = get_project_name($project_id);
					echo "<h3>Project $project_id -- $project_name</h3>";
					if(mysql_num_rows($result2) >0){//check new message
						echo "<div class = 'message'>";
						while($row2 = mysql_fetch_assoc($result2)){
							$message = $row2['message'];
							$from = $row2['employeeID'];
							echo "Message:  $message || From:  $from<br>";
						}
						echo "</div>";
					}else{
						echo "No message today<br><br>";
					/*
						//if there is no new message tell when is the recent update
						$message_result = mysql_query("SELECT submit_date FROM project_message WHERE project_id = '$project_id' ORDER BY submit_date DESC limit 1");
						if(mysql_num_rows($message_result)>0){
							$recent_message = mysql_result($message_result,0);
							echo "The most recent message is on $recent_message <br><br>";
						}else{
							echo "No message for this project so far<br><br>";
						}
					*/
					}//check new message
					
					
					$sql3 = "SELECT * FROM todo_list_create WHERE project_id = '$project_id'";
					$result3 = mysql_query($sql3);//check todo list
					
					echo "<form action = 'catch_up_check.php' method = 'post'>";//post this
					if(mysql_num_rows($result3) >0){//if there is any list
						while($row3 = mysql_fetch_assoc($result3)){//go through each list
							$list_name = $row3['todo_list_name'];
							$list_id = $row3['todo_list_id'];
				
							if(list_has_update($list_id,$today_date)){//there is an update
								$new_task_exist = 1;
								echo "<div class = 'todo_list'>";
								echo "Todo List: $list_name<ul>";
								$sql4 = "SELECT * FROM todo_list_task WHERE todo_list_id = '$list_id'";
								$result4 = mysql_query($sql4);
								while($row4 = mysql_fetch_assoc($result4)){//go through each task
									$task_name = $row4['task_name'];
									$task_id = $row4['task_id'];
									$create_date = $row4['create_date'];
									if($create_date == $today_date||task_complete_date($task_id)==$today_date){
										if(task_complete($task_id)){
											$complete_date = task_complete_date($task_id);
											echo "<li><input type='checkbox' name = 'task[]' value = '$task_id' checked> $task_name | Completed on: $complete_date</li>";
										}else{
											echo "<li><input type='checkbox' name = 'task[]' value = '$task_id'> $task_name</li>";
										}//check if the task is already completed	
									}
								}
								echo "</div>";
							}/*else{//there isn't an update
							
								echo "<div class = 'todo_list'>";
								echo "Todo List: $list_name<ul>";
								echo "No task is created or completed today<br>";
								
							//-----------------check the recently created task  ----------------------------------------------------------	
								echo "Todo List: $list_name<ul>";
								$sql4 = "SELECT * FROM todo_list_task WHERE todo_list_id = '$list_id' ORDER BY create_date DESC";
								$result4 = mysql_query($sql4);
								if(mysql_num_rows($result4)>0){
									$t_create_counter = 0;
									while($row4 = mysql_fetch_assoc($result4)){
										$t_create_date = $row4['create_date'];
										if(strtotime($t_create_date)<strtotime($today_date)&& ($t_create_counter ==0)){
											$t_cr = $t_create_date;
											$t_create_counter = 1;	
										}
									}
									if($t_create_counter==1){
										echo "The most recently created task is on date $t_cr <br>";
									}else{
										echo "No recently created task<br>";
									}
								}else{
									echo "There is no task yet <br>";
								}
							//----------------check the recently created task ---------------------------------------------------	
							
							
							
							//------------check the recently completed task ---------------------------------------------------
								$t_sql = "SELECT * FROM todo_list_task INNER JOIN todo_list_complete WHERE todo_list_task.task_id = todo_list_complete.task_id 
										  AND todo_list_task.todo_list_id = '$list_id' ORDER BY todo_list_complete.complete_date DESC;";
								$t_result = mysql_query($t_sql);
								if(mysql_num_rows($t_result)>0){
									$t_com_counter = 0;
									while($t_row = mysql_fetch_assoc($t_result)){
										$t_com_date = $t_row['complete_date'];
										if(strtotime($t_com_date)<strtotime($today_date)&& ($t_com_counter == 0)){
											$t_com = $t_com_date;
											$t_com_counter = 1;
										}
									}
									if($t_com_counter==1){
										echo "The most recently completed task is on date $t_com <br>";
									}else{
										echo "No recently completed task<br>";
									}
								}else{
									echo "No task was completed yet<br>";
								}
							//-----------------check the recently completed task ---------------------------------------------------
							
								echo "</div>";
							}
							echo "</ul>";	*/
						}//go through each list	
					}else{
						echo "There is no task for this project";
					}
				
					
					echo "<input type = 'hidden' name = 'project_id' value = '$project_id'>";
					if($new_task_exist == 1){
						echo "<input type='submit' name='submit' value='Update'>";
					}
					echo "</form></div>";
				}//loop through each project
			}
//---------------------------------------------------------------------------------------------------------------------------------------------------
//---------------------------------------------------End---------------------------------------------------------------------------------------------

	}else{//show other date's info
		$today_date = clean($_POST['check_date']);
		if(empty($today_date)){
			echo "Date can't be empty";
			echo "</div>";
		}else{//non-empty date is entered
			echo "<div class = 'update'>"; 
			echo "<h2>Catch up on $today_date</h2></div>";

//--------------------------------------------------------------------------------------------------------------------------------------
//------------------------------------The following will be the same in the if clause---------------------------------------------------
			if(!$_SESSION['admin']){
				$sql = "SELECT * FROM project_assign WHERE employeeID = '$e_id'";
			}else{
				$sql = "SELECT *FROM project";
			}
			$result = mysql_query($sql);
			
			if(mysql_num_rows($result) == 0){
				echo "You don't have any project yet";
			}else{
				while($row = mysql_fetch_assoc($result)){//1st loop go through each project
					echo "<div class = 'main_form'>";
					$new_task_exist = 0; //set it back to 0
					$project_id = $row['project_id'];
					$sql2 = "SELECT * FROM project_message WHERE project_id = '$project_id' AND submit_date = '$today_date'";
					$result2 = mysql_query($sql2);
					
					$project_name = get_project_name($project_id);
					echo "<h3>Project $project_id -- $project_name</h3>";
					if(mysql_num_rows($result2) >0){//check new message
						echo "<div class = 'message'>";
						while($row2 = mysql_fetch_assoc($result2)){
							$message = $row2['message'];
							$from = $row2['employeeID'];
							echo "Message:  $message || From:  $from<br>";
						}
						echo "</div>";
					}else{
						echo "No message today<br><br>";
					/*
						//if there is no new message tell when is the recent update
						$message_result = mysql_query("SELECT submit_date FROM project_message WHERE project_id = '$project_id' ORDER BY submit_date DESC");
						if(mysql_num_rows($message_result)>0){//there were previous updates
							$m_counter = 0;
							while($m_row = mysql_fetch_assoc($message_result)){
								$m_date = $m_row['submit_date'];
								if((strtotime($m_date)<strtotime($today_date))&&($m_counter==0)){
									$m_counter = 1;
									$m_recent_date = $m_date;
								}
							}
							if($m_counter==1){
								echo "The most recent message is on $m_recent_date <br><br>";
							}else{
								echo "No message for this project so far<br><br>";
							}
						}else{
							echo "No message for this project so far<br><br>";
						}
					*/
					}//check new message
					
					$sql3 = "SELECT * FROM todo_list_create WHERE project_id = '$project_id'";
					$result3 = mysql_query($sql3);//check todo list
					
					echo "<form action = 'catch_up_check.php' method = 'post'>";//post this
					if(mysql_num_rows($result3) >0){//if there is any list
						while($row3 = mysql_fetch_assoc($result3)){//go through each list
							$list_name = $row3['todo_list_name'];
							$list_id = $row3['todo_list_id'];
				
							if(list_has_update($list_id,$today_date)){//there is update
								$new_task_exist = 1;
								echo "<div class = 'todo_list'>";
								echo "Todo List: $list_name<ul>";
								$sql4 = "SELECT * FROM todo_list_task WHERE todo_list_id = '$list_id'";
								$result4 = mysql_query($sql4);
								while($row4 = mysql_fetch_assoc($result4)){//go through each task
									$task_name = $row4['task_name'];
									$task_id = $row4['task_id'];
									$create_date = $row4['create_date'];
									if($create_date == $today_date||task_complete_date($task_id)==$today_date){
										if(task_complete($task_id)){
											$complete_date = task_complete_date($task_id);
											echo "<li><input type='checkbox' name = 'task[]' value = '$task_id' checked> $task_name | Completed on: $complete_date</li>";
										}else{
											echo "<li><input type='checkbox' name = 'task[]' value = '$task_id'> $task_name</li>";
										}//check if the task is already completed
									}
								}
								echo "</div>";
							}/*else{//there isn't an update
							
								echo "<div class = 'todo_list'>";
								echo "Todo List: $list_name<ul>";
								echo "No task is created or completed today<br>";
							
							//-----------------check the recently created task  ----------------------------------------------------------	
								echo "Todo List: $list_name<ul>";
								$sql4 = "SELECT * FROM todo_list_task WHERE todo_list_id = '$list_id' ORDER BY create_date DESC";
								$result4 = mysql_query($sql4);
								if(mysql_num_rows($result4)>0){
									$t_create_counter = 0;
									while($row4 = mysql_fetch_assoc($result4)){
										$t_create_date = $row4['create_date'];
										if(strtotime($t_create_date)<strtotime($today_date)&& ($t_create_counter ==0)){
											$t_cr = $t_create_date;
											$t_create_counter = 1;	
										}
									}
									if($t_create_counter==1){
										echo "The most recently created task is on date $t_cr <br>";
									}else{
										echo "No recently created task<br>";
									}
								}else{
									echo "There is no task yet <br>";
								}
							//----------------check the recently created task ---------------------------------------------------	
							
							//------------check the recently completed task ---------------------------------------------------
								$t_sql = "SELECT * FROM todo_list_task INNER JOIN todo_list_complete WHERE todo_list_task.task_id = todo_list_complete.task_id 
										  AND todo_list_task.todo_list_id = '$list_id' ORDER BY todo_list_complete.complete_date DESC;";
								$t_result = mysql_query($t_sql);
								if(mysql_num_rows($t_result)>0){
									$t_com_counter = 0;
									while($t_row = mysql_fetch_assoc($t_result)){
										$t_com_date = $t_row['complete_date'];
										if(strtotime($t_com_date)<strtotime($today_date)&& ($t_com_counter == 0)){
											$t_com = $t_com_date;
											$t_com_counter = 1;
										}
									}
									if($t_com_counter==1){
										echo "The most recently completed task is on date $t_com <br>";
									}else{
										echo "No recently completed task<br>";
									}
								}else{
									echo "No task was completed yet<br>";
								}
							//-----------------check the recently completed task ---------------------------------------------------
							
								echo "</div>";
						
							}
							echo "</ul>";	*/
						}//go through each list
						
					}else{
						echo "There is no task for this project";
					}
					
					
					echo "<input type = 'hidden' name = 'project_id' value = '$project_id'>";
					if($new_task_exist == 1){
						echo "<input type='submit' name='submit' value='Update'>";
					}
					echo "</form></div>";
				}//loop through each project
			}
//---------------------------------------------------------------------------------------------------------------------------------------------------
//---------------------------------------------------End---------------------------------------------------------------------------------------------	
		}
	}
?>

<?php
include_once 'include/overall/overallfooter.php'; 
?>