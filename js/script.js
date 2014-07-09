$(document).ready(function() {

	$.ajaxSetup({
		type: "POST"
	});

	$(document).ajaxStart(function() {
		$("body").addClass("loading");
	});
	$(document).ajaxStop(function() {
		$("body").removeClass("loading");
	});
	
	$("#logout-link").click(function() {
		logoutUser();
		return false;
	});
	
	$("#refresh-servers").click(function() {
		refreshServers();
		return false;
	});
	
	$("#login").submit(function(){
		login();
		return false;
	});
	
	$("#create-account").submit(function(){
		createAccount();
		return false;
	});
	
	
	$("#create-username").keyup(function(){
		$("#username-check").html(checkUsername($("#create-username").val()));
	});
	$("#create-password").keyup(function(){
		$("#password-check").html(checkPasswordStrength($("#create-password").val()));
	});
	$("#create-password-again").keyup(function(){
		$("#password-match").html(checkPasswordMatch());
	});
	
	function createAccount() {
		$.post("createuser.php", $("#create-account").serialize(), function(data) { 
			if(data.result) {
				createSuccess(data); 
			} else {
				createFailure(data);
			}
		},
		"json");
	}
	
	function createSuccess(data) {
		alert(data.message);
		setTimeout(refreshServers(),200);
	}
	
	function createFailure(data) {
		alert(data.message);
	}
	
	
	function login() {
		$.post("loginuser.php", $("#login").serialize(), function(data) { 
			if(data.result) {
				loginSuccess(data); 
			} else {
				loginFailure(data);
			}
		},
		"json");
	}
	
	function loginSuccess(data) {
		$("#logged-out").addClass("hide");
		$("#logged-in").removeClass("hide");
		$("#username-text").html(data.username);
		setTimeout(refreshServers(),200);
	}
	
	function loginFailure(data) {
		alert(data.message);
	}
	
	function logoutUser() {
		$.post("logoutuser.php",function(data) {
			$("#logged-out").removeClass("hide");
			$("#logged-in").addClass("hide");
			setTimeout(refreshServers(),200);
		});
	}
	
	function refreshServers() {
		$.post("servers.php",function(data) {
			$("#server-container").html(data);
		});
	}
	
	function checkUsername(username) {
		var result = $("#username-check");
		
		if(username.length == 0) {
			result.removeClass();
			result.addClass("label hide");
			return "";
		}
		if(username.length > 16) {
			result.removeClass();
			result.addClass("label label-important");
			return "Too long";
		} else if(username.match(/^(\w|-){1,16}$/) === null) {
			result.removeClass();
			result.addClass("label label-important");
			return "Allowed characters: a-z,A-Z,0-9,-,_";
		} else {
			result.removeClass();
			result.addClass("label hide");
			return "";
		}
	}
	
	function checkPasswordStrength(password) {
	    var strength = 0;
		var result = $("#password-check");
	
		if(password.length === 0) {
			result.removeClass();
			result.addClass("label hide");
			return "";
		}
	
		if(password.length < 6) {
			result.removeClass();
			result.addClass("label label-important");
			return "Too short";
		}

		if(password.length > 7) strength += 1

		//If password contains both lower and uppercase characters
		if(password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/)) strength += 1;

		//If password contains numbers and characters
		if(password.match(/([a-zA-Z])/) && password.match(/([0-9])/)) strength += 1;

		//If password contains one special character
		if(password.match(/([!,%,&,@,#,$,^,*,?,_,~])/)) strength += 1;

		//If password contains al least two special characters
		if(password.match(/(.*[!,%,&,@,#,$,^,*,?,_,~].*[!,%,&,@,#,$,^,*,?,_,~])/)) strength += 1;

		if(strength < 2) {
			result.removeClass();
			result.addClass("label label-important");
			return "Weak"
		} else if(strength == 2) {
			result.removeClass();
			result.addClass("label label-warning");
			return "OK";
		} else {
			result.removeClass();
			result.addClass("label label-success");
			return "Strong";
		}
	}
	
	function checkPasswordMatch() {
		var result = $("#password-match");
		var password = $("#create-password");
		var passwordAgain = $("#create-password-again");
		
		if(password.val().length === 0) {
			result.removeClass();
			result.addClass("label hide");
			return "";
		} else if(password.val() !== passwordAgain.val()) {
			result.removeClass();
			result.addClass("label label-warning");
			return "Doesn't match";
		} else {
			result.removeClass();
			result.addClass("label label-success");
			return "Match!";
		}
	}
});