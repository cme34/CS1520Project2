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
	<title>Login</title>
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
		<h3>Login</h3>
		<form Action="../php scripts/loginScript.php" Method="POST">
			Username: <input id="username" name="username" type="text" maxlength=64></input>
			Password: <input id="password" name="password" type="password" maxlength=256></input>
			<button class="medium success button" id="button-login">Login</button>
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
			if (!$('#username').val() || !$('#password').val()) {
				$('.error-text').remove();
				$('form').append('<p class="error-text">Feilds must be filled.</br></p>');
				return false;
			}
		});
	</script>
</body>
</html>