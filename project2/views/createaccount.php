<?php
//If the user is signed in, prevent them from accessing this page
session_start();
if (isset($_SESSION["username"])) {
	header("Location: home.php");
}
?>

<!doctype html>
<html>
<head>
	<title>Create Account</title>
	<meta charset="utf-8" />
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="stylesheet" href="../css/foundation.css" />
	<link rel="stylesheet" href="../css/app.css" />
	<?php include '../php scripts/navigator.php';?>
</head>
<body>
	<?php
	createNavigator(); //Create the navigator at the top of the page. This is defined in navigator.php
	?>
	<div class="site-container medium">
		<h3>Create an Account</h3>
		<form Action="../php scripts/createAccountScript.php" Method="POST">
			Email: <input class="text-feild" id="email" name="email" type="text" maxlength=256></input>
			<p class="character-limit-text">Character Limit: 256</p>
			Username: <input class="text-feild" id="username" name="username" type="text" maxlength=64></input>
			<p class="character-limit-text">Character Limit: 64</p>
			Password: <input class="text-feild" id="password" name="password" type="password" maxlength=256></input>
			<p class="character-limit-text">Character Limit: 256</p>
			Confirm Password: <input class="text-feild" id="passwordConfirm" name="passwordConfirm" type="password" maxlength=256></input>
			<p class="character-limit-text">Character Limit: 256</p>
			</br>
			<button class="medium success button" id="button-createaccount-submit">Create Account</button>
			<a href="home.php"><div class="medium secondary button" id="button-createaccount-cancel">Cancel</div></a>
			<?php
				if (isset($_SESSION["error"])) {
					$err = $_SESSION["error"];
					unset($_SESSION["error"]);
					echo "<p class='error-text'>$err</br></p>";
				}
			?>
		</form>
	</div>
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
	<script src="../js/myaccount.js"></script>
	<script type="text/javascript">
		$('form').on('submit', function() {
			var email = $('#email').val();
			var username = $('#username').val();
			var password = $('#password').val();
			var passwordConfirm = $('#passwordConfirm').val();
			
			//Is any feild empty
			if (!email || !username || !password || !passwordConfirm) {
				$('.error-text').remove();
				$('form').append('<p class="error-text">Feilds must be filled.</br></p>');
				return false;
			}
			//Does password equal passwordConfirm
			else if (password != passwordConfirm) {
				$('.error-text').remove();
				$('form').append('<p class="error-text">Password does not match confirm password.</br></p>');
				return false;
			}
			//Is username too short
			else if (username.length < 8) {
				$('.error-text').remove();
				$('form').append('<p class="error-text">Username is too short. Must be at least 8 characters long.</br></p>');
				return false;
			}
			//Is password too short
			else if (password.length < 8) {
				$('.error-text').remove();
				$('form').append('<p class="error-text">Password is too short. Must be at least 8 characters long.</br></p>');
				return false;
			}
			//Does password fulfill all requirments
			var contains_upper = 0;
			var contains_lower = 0;
			var contains_number = 0;
			var contains_symbol = 0;
			for (var i = 0; i < password.length; i++) {
				//Contians upper case
				if (password.charCodeAt(i) > 64 && password.charCodeAt(i) < 91) {
					contains_upper = 1;
				}
				//Contains lower case
				else if (password.charCodeAt(i) > 96 && password.charCodeAt(i) < 123) {
					contains_lower = 1;
				}
				//Contains number
				else if (password.charCodeAt(i) > 47 && password.charCodeAt(i) < 58) {
					contains_number = 1;
				}
				//Contains symbol
				else if (password.charCodeAt(i) > 32 && password.charCodeAt(i) < 48) {
					contains_symbol = 1;
				}
				else if (password.charCodeAt(i) > 57 && password.charCodeAt(i) < 65) {
					contains_symbol = 1;
				}
				else if (password.charCodeAt(i) > 90 && password.charCodeAt(i) < 97) {
					contains_symbol = 1;
				}
				else if (password.charCodeAt(i) > 122 && password.charCodeAt(i) < 127) {
					contains_symbol = 1;
				}
			}
			var fulfills = contains_upper + contains_lower + contains_number + contains_symbol;
			if (fulfills < 3) {
				$('.error-text').remove();
				$('form').append('<p class="error-text">Password is too weak. Make sure it contains 3 of the following: ' + 
					'an upper case letter, lower case letter, number and symbol.</br></p>');
				return false;
			}
		});
	</script>
</body>
</html>