<?php 
include_once 'core/ini.php';
include_once 'include/overall/overallheader.php'; 
page_protect();

echo "<div class = 'main_form'>";
echo "<h2>Project Page</h2><ul>";

$e_id = $_SESSION['user_id'];

if(!$_SESSION['admin']){
	$result = mysql_query("SELECT * FROM project_assign WHERE employeeID = '$e_id'");
}else{
	$result = mysql_query("SELECT * FROM project");
}//admin can view all project, employee can only view project assigned

$num_project = mysql_num_rows($result);

if($num_project<=0){//no result, no project
	echo "You have no project right now";
}else{
	while ($row = mysql_fetch_assoc($result)){
		$project_id = $row['project_id'];
		$project = get_project_name($project_id);
		echo "<li>Project No.$project_id: $project <a href='user_project_message.php?project_id=$project_id'> report </a>
		<a href='todo_list_view.php?project_id=$project_id'> view to-do list  </a> 
		<a href='todo_list_create1.php?project_id=$project_id'> create to-do list  </a></li>";//make a link
	}
}

echo "</ul></div>";
include_once 'include/overall/overallfooter.php'; 
?>