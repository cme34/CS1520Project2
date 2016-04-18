<?php
//If didn't enter the page from changepassword or changeemail, then navigate to home.php 
session_Start();
if (!isset($_SESSION["changed"])) {
	header("Location: home.php");
}
?>

<!doctype html>
<html>
<head>
	<title>Changed Succesful</title>
	<meta charset="utf-8" />
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="stylesheet" href="../css/foundation.css" />
	<link rel="stylesheet" href="../css/app.css" />
	<?php include '../php scripts/navigator.php';?>
</head>
<body>
	<?php
	//Get variable from SESSION
	$changed = $_SESSION["changed"];
	unset($_SESSION["changed"]);

	//Create Page
	createNavigator(); //Create the navigator at the top of the page. This is defined in navigator.php
	
	echo "<p class='success-text'>You have changed your $changed successfully.</p>";
	echo "<a href='home.php'><div class='medium success button back-to-home'>Back to home page</div></a>";
	?>
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
	<script src="../js/myaccount.js"></script>
</body>
</html>