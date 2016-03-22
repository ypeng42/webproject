<?php
include_once 'core/ini.php';
include_once 'include/overall/overallheader.php'; 
admin_protect();
$error = array();

	echo "<h2>Create New Employee Account</h2>";
if(empty($_POST) == false){
	$first_name = clean($_POST['first_name']);
	$middle_name = clean($_POST['mid_name']);
	$last_name = clean($_POST['last_name']);
	$email = clean($_POST['email']);
	$address = clean($_POST['address']);
	$city = clean($_POST['city']);
	$country = clean($_POST['country']);
	$postcode = clean($_POST['postcode']);
	$current_salary = clean($_POST['current_salary']);
	$hourly_salary = clean($_POST['hourly_salary']);
	
	if(is_numeric($current_salary)==false||is_numeric($hourly_salary)==false){
		$error[] =  "Please give valid input<br><br>";
	}else if(empty($first_name)||empty($last_name)||empty($email)){
		$error[] = "Please enter the required field<br><br>";
	}else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
		$error[] =  "Please enter a valid email address<br><br>";
	}else{	
		$e_id = substr($first_name,0,1).substr($last_name,0,3).rand(0,1000);//ONLY EMPLOYEE HAS THIS UNIQUE ID!
		$rand_password = md5(rand(0,1000));
		$sql = "INSERT INTO user (username,password,firstName,midName,lastName,address,city,postcode,country,salary,hourSalary,enroll,email,employeeID) VALUES ('$e_id','$rand_password','$first_name','$middle_name','$last_name','$address','$city',$postcode,'$country',$current_salary,$hourly_salary,'1','$email','$e_id')";
		mysql_query($sql);//insert info

		$server = "mail.pengmaomao.com";

		ini_set("SMTP",$server);
		$body = "Hello $first_name,\n
	A account has been created for you.
	Your username is '$e_id'
	Your password is '$rand_password'
	You can reset your password at the website www.pengmaomao.com";

		mail($email,"Account Activation",$body,"From yuqing's website");
	}
	
	if(empty($error)){
		echo "You have create an account";
	}else{
		echo output_errors($error);
	}
}
?>
<html>
<form action= "make_employee.php" method = "post">
	<div class="main_form">
	<ul>
		<li>*First Name:<br><input type="text" name="first_name"></li>
	
		<li>Middle Name::<br><input type="text" name="mid_name"></li>
		
		<li>*Last Name:<br><input type="text" name="last_name"></li>
	
		<li>*Email:<br><input type="text" name="email"></li>
		
		<li>Address:<br><input type="text" name="address"></li>
		
		<li>City:<br><input type="text" name="city"></li>
	
		<li>Country:<br><input type="text" name="country"></li>
		
		<li>PostCode:<br><input type="text" name="postcode"></li>
	
		<li>*Current Salary:<br><input type="text" name="current_salary"></li>
	
		<li>*Hourly Salary:<br><input type="text" name="hourly_salary"></li>
	
		<li><input type="submit" name= "submit" value= "Create"></li>
	</ul>
	</div>
</form>
<?php include_once 'include/overall/overallfooter.php'; ?>