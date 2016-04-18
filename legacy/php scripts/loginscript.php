<?php
session_start();

//Obtain form data
$username = $_POST["username"];
$password = $_POST["password"];
$username = rtrim($username);
$password = rtrim($password);

//Connect to database
$db = new mysqli('localhost', 'root', '', 'estock');
if ($db->connect_error) {
	$_SESSION["error"] = "Connection with database failed. Please try again later.";
	header("Location: ../views/login.php");
	die();
}

//Sterilize Inputs
$username = mysql_real_escape_string($username);
$password = mysql_real_escape_string($password);

//Hash password
$password = hash("sha256", $password);

//Check if credentials are valid
$query = "SELECT * FROM accountinfo WHERE accountinfo.username = '$username' AND accountinfo.password = '$password'";
$result = $db->query($query);
$rows = $result->num_rows;
if ($rows < 1) {
	//Set error message and go to login page
	$_SESSION["error"] = "Invalid login credentials";
	header("Location: ../views/login.php");
	die();
}

//Set session data and go to home page
$_SESSION["username"] = $username;
header("Location: ../views/home.php");
?>