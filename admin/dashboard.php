<?php
include('../class/User.php');
$user = new User();
$user->adminLoginStatus();
include('include/header.php');
?>
<title> User Management System with PHP & MySQL</title>
<link rel="stylesheet" href="css/style.css">
<?php include('include/container.php'); ?>
<div class="container contact">
	<h2>Example: User Management System with PHP & MySQL</h2>
	<?php include 'menus.php'; ?>
	<div class="col-lg-10 col-md-10 col-sm-9 col-xs-12">
		<a href="#"><strong><span class="fa fa-dashboard"></span> My Dashboard</strong></a>
		<hr>
		<div class="row">
			<div class="col-md-3">
				<div class="panel panel-default">
					<div class="panel-body bk-primary text-light">
						<div class="stat-panel text-center">
							<div class="stat-panel-number h1 "><?php echo $user->totalUsers(""); ?></div>
							<div class="stat-panel-title text-uppercase">Total Users</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php include('include/footer.php'); ?>