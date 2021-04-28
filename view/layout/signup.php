<!DOCTYPE html>
<html>
<script type="text/javascript">
	function pre_proc(){
		var PName = $('#PName').val();
		var PUsername = $('#PUsername').val();
		var PEmail = $('#PEmail').val();
		var PAddress = $('#PAddress').val();
		var PPhone = $('#PPhone').val();
		var PPassword = $('#PPassword').val();

		if(PName.length <= 0){
			alert('Please fill the name');
		}else{
			if(PUsername.length <= 0){
				alert('Please fill the username');
			}else{
				if(PEmail.length <= 0){
					alert('Please fill the email');
				}else{
					if(PAddress.length <= 5){
						alert('Please fill your Address more than 5 char');
					}else{
						if(PPhone.length <= 0){
							alert('Please fill your phone number');
						}else{
							if(PPassword.length <= 0){
								alert('Please fill your password');
							}else{
								var param = {
									'PUsername':PUsername,
									'PEmail':PEmail,
									'PPhone':PPhone,
									'PName':PName,
									'PAddress':PAddress,
									'PPassword':PPassword
								};
								signup_proc(param);
							}
						}
					}
				}
			}
		}
	}
</script>
<body>
	<style type="text/css">
		.bold{
			font-weight: bold;
		}
	</style>
	<div class="container mr-auto">
		<div class="card">
			<div class="card-header bg-secondary">
				<h3 class="text-white">Signup Form</h3>
			</div>
			<div class="card-body">
				<div role="form">
					<p class="bold">Name</p>
					<input type="text" id="PName" placeholder="Your Name" class="form-control">
					<p class="bold">Username</p>
					<input type="text" id="PUsername" placeholder="New Username" class="form-control">
					<p class="bold">Email</p>
					<input type="email" id="PEmail" placeholder="New Email" class="form-control">
					<p class="bold">Phone Number</p>
					<input type="number" id="PPhone" placeholder="Your Phone Number" class="form-control">
					<p class="bold">Address</p>
					<textarea class="form-control" id="PAddress">Your Home Address</textarea>
					<p class="bold">Password</p>
					<input type="password" id="PPassword" class="form-control" placeholder="New Password">
					<br/>
					<button role="button" class="btn btn-info" onclick="pre_proc()">Signup</button>
					<p> JSON Response </p>
					<div class="container mr-auto">
						<p id="signup_response"></p>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>