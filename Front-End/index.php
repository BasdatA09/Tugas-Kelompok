<!DOCTYPE html>
<html>
	<head>
		<title>SiSidang</title>
		<link rel="stylesheet" href="assets/css/vendor.css" />
		<link rel="stylesheet" href="assets/css/app.css" />
	</head>
	<body>
		<div class="row indexPage">
			<div class="small-12 columns">
				<div class="hero">
					<div class="row expanded">
						<div class="small-12 medium-4 columns">
							<h1 class="logo"><span>Si</span>Sidang</h1>
						</div>
						<div class="small-12 medium-offset-2 medium-6 columns right-panel">
							<from class="row expanded" method="post" action="home.php">
								<div class="small-12 medium-5 columns">
									<input type="text" placeholder="Username anda" />
								</div>
								<div class="small-12 medium-5 columns">
									<input type="password" placeholder="Password anda" />
								</div>
								<div class="small-12 medium-2 columns">
									<input type="submit" value="masuk" onClick="location.href = './home.php';"/>
								</div>
							</from>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php include 'footer.php' ?>
		<?php include 'js.php' ?>
	</body>
</html>