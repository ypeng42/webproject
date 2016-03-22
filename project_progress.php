<?php 
include_once 'core/ini.php';
include_once 'include/overall/overallheader.php'; 
admin_protect();

echo "<div class = 'main_form'><h2>Project Page</h2><ul>";
$sql = "SELECT * FROM project";
$result = mysql_query($sql);

if(mysql_num_rows($result)<=0){
	echo "<li>There is no project right now</li>";
}else{
	while ($row = mysql_fetch_assoc($result)){//loop through each project name
		$project_id = $row['project_id'];
		$project = $row['project_name'];
		echo "<li>Project No.$project_id: $project <a href='project_progress2.php?project_id=$project_id'> view </a> 
		<a href='project_name_change.php?project_id=$project_id'> change name  </a> 
		<a href ='project_add_people.php?project_id=$project_id'>add employee</a></li>";//create link
	}
}
echo "</ul></div>";
include_once 'include/overall/overallfooter.php'; 
?>