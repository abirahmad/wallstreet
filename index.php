<?php 
include('class/User.php');
$user = new User();
$user->loginStatus();
include('include/header.php');
?>
<title> User Management System with PHP & MySQL</title>
<?php include('include/container.php');?>
<div class="container contact">	
	<h2>Example: User Management System with PHP & MySQL</h2>	
	<?php include('menu.php');?>
	<div class="table-responsive">	
	User Dashboard!
	</div>
</div>	
<?php include('include/footer.php');?>