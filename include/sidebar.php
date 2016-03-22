<div id="side">
	<?php
	if(logged_in()){
		include 'include/sidebar_widget/employee_loggedin.php';
		if($_SESSION['admin']){
			include 'include/sidebar_widget/admin_side.php';
		}else{
			include 'include/sidebar_widget/employee_side.php';
		}
	}else{
		include 'include/sidebar_widget/login_form.php';
	}
	?>
</div>

