
	<!DOCTYPE html>
	<html>
	
	<body>
		<div class="container">
			<div class="card">
				<div class="card-header bg-secondary">
					<h3 class="text-white"> Login Form </h3>
				</div>
				<div class="card-body">
					<form role="form" action="#">
						<p><b>Username</b></p>
						<input type="text" id="username" class="form-control">
						<p><b>Password</b></p>
						<input type="password" id="password" class="form-control">
						<br/>
						<button role="button" class="btn btn-info mb-5" onclick="login_proc($('#username').val(), $('#password').val())">Login</button>
					</form>
					<p> JSON Response </p>
					<div class="container mr-auto bg-success">
						<p id="result"></p>
					</div>
				</div>
			</div>
		</div>
	</body>
	</html>
