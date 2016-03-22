<?php 
include_once 'core/ini.php';
include 'include/overall/overallheader.php'; 

$error = array();
$random = md5(rand(12345,6789534));//a random number
echo "<h1>Register</h1>";
if(empty($_POST) == false){
	$username = clean($_POST['username']);
	$password = clean($_POST['password']);
	$email = clean($_POST['email']);
	
	if(empty($username)==true||empty($password)==true||empty($email)==true){
		$error[] = "Please enter all the fields<br>";
	}else if(user_exists($username)){
		$error[] = "The username has already been registered. Please change one.<br>";
	}else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
		$error[] = "Please enter a correct email address";
	}else{
		$sql="INSERT INTO user (username,password,randomnum,enroll,email) VALUES ('$username','$password','$random','0','$email')";
		mysql_query($sql);

		$server = "mail.pengmaomao.com";
		ini_set("SMTP",$server);
		
		$body = "Hello $username,\n\n
You need to activate your account with the link below:
http://www.pengmaomao.com/activate.php?username=$username&code=$random\n\n 
Thank you!";
			
		mail($email,"Account Activation",$body,"From yuqing's website");
	}
	
	if(empty($error)){
		echo "You have registered. Please check your email.";
	}else{
		echo output_errors($error);
	}
}
?>

<ul>
	<form action="register.php" method="post">
	<li>
		Username: <br>
		<input type="text" name="username">
	</li>
	<li>
		Password: <br>
		<input type="password" name="password">
	</li>
	<li>
		Email: <br>
		<input type="text" name="email">
	</li>
	<li>
		<input type="submit" name= "submit" value= "Sign up">
	</li>
</ul>
<?php include 'include/overall/overallfooter.php'; ?>