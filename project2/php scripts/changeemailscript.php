<?php
session_start();

//Obtain form data
$oldemail = $_POST["oldemail"];	
$newemail = $_POST["newemail"];	
$password = $_POST["password"];	

$oldemail = rtrim($oldemail);
$newemail = rtrim($newemail);
$password = rtrim($password);

//Connect to database
$db = new mysqli('localhost', 'root', '', 'estock');
if ($db->connect_error) {
	$_SESSION["error"] = "Connection with database failed. Please try again later.";
	header("Location: ../views/changeemail.php");
	die();
}

//Sterilize Inputs
$username = $_SESSION["username"];
$username = mysql_real_escape_string($username);
$oldemail = mysql_real_escape_string($oldemail);
$newemail = mysql_real_escape_string($newemail);
$password = mysql_real_escape_string($password);

//Hash passwords
$password = hash("sha256", $password);

//Check if email matches
$query = "SELECT * FROM accountinfo WHERE accountinfo.username = '$username' AND accountinfo.email = '$oldemail'";
$result = $db->query($query);
$rows = $result->num_rows;
if ($rows < 1) {
	//Set error message and go to login page
	$_SESSION["error"] = "Old email is incorrect.";
	header("Location: ../views/changeemail.php");
	die();
}

//Check if password matches
$query = "SELECT * FROM accountinfo WHERE accountinfo.username = '$username' AND accountinfo.password = '$password'";
$result = $db->query($query);
$rows = $result->num_rows;
if ($rows < 1) {
	//Set error message and go to login page
	$_SESSION["error"] = "Password is incorrect.";
	header("Location: ../views/changeemail.php");
	die();
}

//Change email in database
$query = "UPDATE accountinfo SET email='$newemail' WHERE username='$username'";
if (!$db->query($query)) {
	$_SESSION["error"] = "An error occured when changing password. Please try again.";
	header("Location: ../views/changeemail.php");
	die();
}

$_SESSION["changed"] = "email";
header("Location: ../views/changed.php");
?>