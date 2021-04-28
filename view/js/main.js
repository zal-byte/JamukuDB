function auth(param){
	if( param == "login"){
		$('#main_container').load('layout/login.php');
	}else if(param == "signup"){
		$("#main_container").load("layout/signup.php");
	}
}
function login_proc(user, pass){
	$.ajax({
		type:'POST',
		url:'../index.php',
		data:'PUsername='+user+'&PPassword='+pass+'&Request=login',
		success:function(a){
			//$param = new URLSearchParams(window.location.search);
			//$param.set('login_status', true);
			//history.replaceState(null, null, '?'+$param.toString());
			$('#result').html(a);
		}
	});
}
function signup_proc(param){
	$.ajax({
		type:'POST',
		url:'../index.php',
		data:'PUsername='+param['PUsername']+'&PName='+param['PName']+'&PEmail='+param['PEmail']+'&PPhone='+param['PPhone']+'&PAddress='+param['PAddress']+'&PPassword='+param['PPassword']+'&Request=signup',
		success:function(res){
			$('#PName').val('');
			$('#PUsername').val('');
			$('#PEmail').val('');
			$('#PPhone').val('');
			$('#PPassword').val('');
			$('#PAddress').val('');
			$('#signup_response').html(res).addClass('bg-success');
		}
	});
}
