<!doctype html>
<html>
<head>
	<title>Change Email</title>
	<meta charset="utf-8" />
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="stylesheet" href="../css/foundation.css" />
	<link rel="stylesheet" href="../css/app.css" />
	<?php include '../php scripts/navigator.php';?>
</head>
<body>
	<?php
	session_Start();
	createNavigator(); //Create the navigator at the top of the page. This is defined in navigator.php
	?>
	<div class="site-container medium">
		<h3>Change Email</h3>
		<form Action="../php scripts/changeemailscript.php" Method="POST">
			Old Email: <input class="text-feild" id="oldemail" name="oldemail" type="text" maxlength=256></input>
			<p class="character-limit-text">Character Limit: 256</p>
			New Email: <input class="text-feild" id="newemail" name="newemail" type="text" maxlength=256></input>
			<p class="character-limit-text">Character Limit: 256</p>
			Password: <input class="text-feild" id="password" name="password" type="password" maxlength=256></input>
			<p class="character-limit-text">Character Limit: 256</p>
			</br>
			<button class="medium success button" id="button-createaccount-submit">Change Email</button>
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
			var oldemail = $('#oldemail').val();
			var newemail = $('#newemail').val();
			var password = $('#password').val();
			
			//Is any feild empty
			if (!oldemail || !newemail || !password) {
				$('.error-text').remove();
				$('form').append('<p class="error-text">Feilds must be filled.</br></p>');
				return false;
			}
		});
	</script>
</body>
</html>