<?php
session_start();

//Obtain form data
$email = $_POST["email"];	
$username = $_POST["username"];	
$password = $_POST["password"];	
$passwordConfirm = $_POST["passwordConfirm"];	

$email = rtrim($email);
$username = rtrim($username);
$password = rtrim($password);
$passwordConfirm = rtrim($passwordConfirm);

//Connect to database
$db = new mysqli('localhost', 'root', '', 'estock');
if ($db->connect_error) {
	$_SESSION["error"] = "Connection with database failed. Please try again later.";
	header("Location: ../views/createaccount.php");
	die();
}

//Sterilize Inputs
$username = mysql_real_escape_string($username);
$password = mysql_real_escape_string($password);
$email = mysql_real_escape_string($email);

//Check if username is taken
$query = "SELECT * FROM accountinfo WHERE accountinfo.username = '$username'";
$result = $db->query($query);
$rows = $result->num_rows;
if ($rows > 0) {
	$_SESSION["error"] = "That username is already in use. Please choose a different one.";
	header("Location: ../views/createaccount.php");
	die();
}

//Hash password
$password = hash("sha256", $password);

//Add new entry to database
$query = "INSERT INTO accountinfo (username, password, email, verified, banned) VALUES ('$username', '$password', '$email', 1, 0)";
if (!$db->query($query)) {
	$_SESSION["error"] = "An error occured when submitting data to the database. Please try again.";
	header("Location: ../views/createaccount.php");
	die();
}

header("Location: ../views/login.php");
?>