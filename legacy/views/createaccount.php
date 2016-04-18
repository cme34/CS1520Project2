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
			Email: <input class="text-feild" name="email" type="text" maxlength=256></input>
			<p class="character-limit-text">Character Limit: 256</p>
			Username: <input class="text-feild" name="username" type="text" maxlength=64></input>
			<p class="character-limit-text">Character Limit: 64</p>
			Password: <input class="text-feild" name="password" type="password" maxlength=256></input>
			<p class="character-limit-text">Character Limit: 256</p>
			Confirm Password: <input class="text-feild" name="passwordConfirm" type="password" maxlength=256></input>
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
</body>
</html>