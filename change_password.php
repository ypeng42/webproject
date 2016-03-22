<?php 
include_once 'core/ini.php';
include 'include/overall/overallheader.php'; 
page_protect();

$error = array();
echo  "<div class= 'main_form'>";
echo "<h2>Change Password</h2>";
if(empty($_POST) == false){
	$username = $_POST['username'];
	$c_password = $_POST['c_password'];
	$n_password = $_POST['n_password'];
	
	if(empty($username)||empty($c_password)||empty($n_password)){
		$error[] = 'You need to enter all fields';
	} else if($user_data['username'] !== $username){
		$error[] = 'Wrong username';
	} else if(login($username,$c_password)==false){
		$error[] = 'Wrong username and password combination';
	} else{
		password_update($username,$n_password);
	}
	
	if(empty($error)){
		echo "You have changed your password!";
	}else{
		echo output_errors($error);
	}
}
?>
<ul>
	<form action="change_password.php" method="post">
		<li>Current Username: <br> <input type="text" name="username"></li>
		
		<li>Current Password: <br> <input type="password" name="c_password"></li>
		
		<li>New Password: <br> <input type="password" name="n_password"></li>
		
		<li><input type="submit" name= "submit" value= "Change"></li>
</ul>
</div>
<?php include 'include/overall/overallfooter.php'; ?>

