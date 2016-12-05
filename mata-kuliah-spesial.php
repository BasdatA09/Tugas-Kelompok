<?php
	session_start();
if(!isset($_SESSION['role'])){
	header('location: login.php');
}

?>

<!DOCTYPE html>
<html>
	<head>
		<title>SiSidang</title>
		  <?php include_once 'favicon.php'; ?>
		<link rel="stylesheet" href="assets/css/vendor.css" />
		<link rel="stylesheet" href="assets/css/app.css" />
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
		<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
		<link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" />

	</head>
	<body>
		<?php include 'header.php' ?>
		<div class="row adminHome">
				<?php include 'mks-table-'.$_SESSION['role']['0'].'.php' ?>
		</div>
		<?php include 'footer.php' ?>
		<?php include 'js.php' ?>
	</body>
</html>