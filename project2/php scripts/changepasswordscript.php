<?php
session_start();

//Obtain form data
$oldpassword = $_POST["oldpassword"];	
$password = $_POST["newpassword"];	
$passwordConfirm = $_POST["passwordconfirm"];	

$oldpassword = rtrim($oldpassword);
$password = rtrim($password);
$passwordConfirm = rtrim($passwordConfirm);

//Connect to database
$db = new mysqli('localhost', 'root', '', 'estock');
if ($db->connect_error) {
	$_SESSION["error"] = "Connection with database failed. Please try again later.";
	header("Location: ../views/changepassword.php");
	die();
}

//Sterilize Inputs
$username = $_SESSION["username"];
$username = mysql_real_escape_string($username);
$oldpassword = mysql_real_escape_string($oldpassword);
$password = mysql_real_escape_string($password);

//Hash passwords
$oldpassword = hash("sha256", $oldpassword);
$password = hash("sha256", $password);

//Check if oldpassword matches
$query = "SELECT * FROM accountinfo WHERE accountinfo.username = '$username' AND accountinfo.password = '$oldpassword'";
$result = $db->query($query);
$rows = $result->num_rows;
if ($rows < 1) {
	//Set error message and go to login page
	$_SESSION["error"] = "Old password is incorrect.";
	header("Location: ../views/changepassword.php");
	die();
}

//Change password in database
$query = "UPDATE accountinfo SET password='$password' WHERE username='$username'";
if (!$db->query($query)) {
	$_SESSION["error"] = "An error occured when changing password. Please try again.";
	header("Location: ../views/changepassword.php");
	die();
}

$_SESSION["changed"] = "password";
header("Location: ../views/changed.php");
?>