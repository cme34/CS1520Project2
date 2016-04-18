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

//Verify provided information meets certain requirments before database connection
//Is any feild empty
if ($email == null || $username == null || $password == null || $passwordConfirm == null) {
	$_SESSION['error'] = "All feilds must be filled";
	header("Location: ../views/createaccount.php");
	die();
}

//Does password equal passwordConfirm
if ($password != $passwordConfirm) {
	$_SESSION['error'] = "Password does not match confirm password.";
	header("Location: ../views/createaccount.php");
	die();
}

//Is username too short
if (strlen($username) < 8) {
	$_SESSION['error'] = "Username is too short. Must be at least 8 characters long.";
	header("Location: ../views/createaccount.php");
	die();
}

//Is password too short
if (strlen($password) < 8) {
	$_SESSION['error'] = "Password is too short. Must be at least 8 characters long.";
	header("Location: ../views/createaccount.php");
	die();
}

//Does password fulfill all requirments
$contains_upper = 0;
$contains_lower = 0;
$contains_number = 0;
$contains_symbol = 0;
$t = $password;
$a = str_split($t);
for ($i = 0; $i < strlen($password); $i++) {
	//Contians upper case
	if (ord($a[$i]) > 64 && ord($a[$i]) < 91) {
		$contains_upper = 1;
	}
	//Contains lower case
	else if (ord($a[$i]) > 96 && ord($a[$i]) < 123) {
		$contains_lower = 1;
	}
	//Contains number
	else if (ord($a[$i]) > 47 && ord($a[$i]) < 58) {
		$contains_number = 1;
	}
	//Contains symbol
	else if (ord($a[$i]) > 32 && ord($a[$i]) < 48) {
		$contains_symbol = 1;
	}
	else if (ord($a[$i]) > 57 && ord($a[$i]) < 65) {
		$contains_symbol = 1;
	}
	else if (ord($a[$i]) > 90 && ord($a[$i]) < 97) {
		$contains_symbol = 1;
	}
	else if (ord($a[$i]) > 122 && ord($a[$i]) < 127) {
		$contains_symbol = 1;
	}
}
$fulfills = $contains_lower + $contains_upper + $contains_number + $contains_symbol;
if ($fulfills < 3) {
	$_SESSION['error'] = "Password is too weak. Make sure it contains 3 of the following: an upper case letter, lower case letter, number and symbol.";
	header("Location: ../views/createaccount.php");
	die();
}



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