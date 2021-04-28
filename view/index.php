<?php
	


	if(isset($_SESSION["loged_in"])){
		dashboard();
	}else{
		login();
	}


	function dashboard(){
		?>
		<!DOCTYPE html>
		<html>
		<head>
			<meta charset="utf-8">
			<meta name="viewport" content="width=device-width,initial-scale=1">
			<meta name="description" content="Jamuku">
			<link rel="stylesheet" type="text/css" href="sytle/bootstrap4.3.1.css">
			<link rel="stylesheet" type="text/css" href="style/jquery-ui.css">
			<title>Jamuku Dashboard</title>
			<script type="text/javascript" src="js/jquery.min.js"></script>
			<script type="text/javascript" src="js/bootstrap.min.js"></script>
			<script type="text/javascript" src="js/popper.min.js"></script>
			<style type="text/css">
				body{
					margin:0;
					padding:0;
				}
			</style>
		</head>
		<body>
		
		</body>
		</html>
		<?php
	}

	function login(){
		?>
		<!DOCTYPE html>
		<html>
		<head>
			<meta charset="utf-8">
			<meta name="viewport" content="width=device-width,initial-scale=1">
			<meta name="description" content="Jamuku">
			<link rel="stylesheet" type="text/css" href="style/bootstrap4.3.1.css">
			<link rel="stylesheet" type="text/css" href="style/jquery-ui.css">
			<title>Jamuku | Login</title>
			<script type="text/javascript" src="js/jquery.min.js"></script>
			<script type="text/javascript" src="js/bootstrap.min.js"></script>
			<script type="text/javascript" src="js/popper.min.js"></script>
			<style type="text/css">
				body{
					margin:0;
					padding:0;
				}
			</style>
		</head>
		<body>
			<div class="container pt-5">
				<div class="row">
					<div class="col-md-5">
						<div class="card border-0 shadow">
							<div class="card-body">
								<center><b>Login</b></center>
								<div class="form-group">
									<label>Username</label>
									<input type="text" id="PUsername" placeholder="Username" class="form-control">
									<label>Password</label>
									<input type="password" id="PPassword" placeholder="Kata Sandi" class="form-control">
									<br/>
									<button type="button" role="button" class="btn btn-info" >Masuk</button>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md">
						<div class="card border-0 shadow">
							<div class="card-body">
								<p>Signup</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</body>	
		</html>	
		<?php
	}


?>