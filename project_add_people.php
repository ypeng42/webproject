<?php 
include_once 'core/ini.php';
include_once 'include/overall/overallheader.php'; 
admin_protect();
$add_error = array();

if(!isset($_POST['add'])){
	$project_id = $_GET['project_id'];
}else{
	$project_id = $_POST['project_id']; 
	$first = clean($_POST['first_name']); 
	$mid = clean($_POST['mid_name']); 
	$last = clean($_POST['last_name']); 

	if(empty($first)||empty($last)){
		$add_error[] = "Please enter all required fields";
	}else{
		if(!get_eid_from_name($first,$mid,$last)){
			$add_error[] = "User doesn't exist";
		}else{
			$e_id = get_eid_from_name($first,$mid,$last);
			if(user_added($e_id,$project_id)){
				$add_error[] =  "Employee already been added";
			}else{
				mysql_query("INSERT INTO project_assign(project_id,employeeID) VALUES('$project_id','$e_id')");
			}
		}
		
	}
}
?>

<html>
<div class = "main_form">
<h2>Add Employee to Project</h2>
<?php
	if(isset($_POST['add'])){
		if(empty($add_error)){
			echo "You have add employee: $e_id";
		}else{
			echo output_errors($add_error);
		}
	}
?>
<form action= "project_add_people.php" method = "post">
	<ul>
		<input type="hidden" name="project_id" value = "<?=$project_id?>">
		
		<li>*Employee First Name: <br> <input type="text" name="first_name"></li>
		
		<li>Employee Middle Name: <br> <input type="text" name="mid_name"></li>
	
		<li>*Employee Last Name: <br>  <input type="text" name="last_name"></li>
	
		<li><input type="submit" name= "add" value= "Add"></li>
    </ul>
</form>
</div>
</html>

<?php include_once 'include/overall/overallfooter.php'; ?>