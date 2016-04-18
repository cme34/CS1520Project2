<?php
session_start();

//Obtain forum data
$poster = $_SESSION["username"];
$replyto = $_GET["replyto"];
$text = $_GET["text"];
$forum = $_GET["forum"];
$threadid = $_GET["threadid"];
$storage = $_GET["storage"];
date_default_timezone_set("EST");
$timestamp = date("m-d-Y h:ia e");

//Sterilize Inputs
$poster = mysql_real_escape_string($poster);
$replyto = mysql_real_escape_string($replyto);
$text = mysql_real_escape_string($text);
$dbforum = mysql_real_escape_string(str_replace(' ', '', strtolower($forum)));
$threadid = mysql_real_escape_string($threadid);

//Determine whether to use file system or database
if ($storage == "fs") {
	//Get commentid and update
	$commentid = "";
	$file = "../forums/$forum/$threadid/commentid.txt";
	if (file_exists($file)) {
		$handle = fopen($file, "r");
		$commentid = fgets($handle);
		fclose($handle);
	}
	$commentid++;
	$handle = fopen($file, "w");
	fwrite($handle, $commentid);
	fclose($handle);
	//Write out comment
	$file = "../forums/$forum/$threadid/$commentid.txt";
	$handle = fopen($file, "w");
	fwrite($handle, "$poster\r\n");
	fwrite($handle, "$timestamp\r\n");
	fwrite($handle, "$replyto\r\n");
	fwrite($handle, "$text\r\n");
	fclose($handle);
}
else {
	//Connect to database
	$db = new mysqli('localhost', 'root', '', 'estock');
	if ($db->connect_error) {
		$_SESSION["error"] = "Connection with database failed. Please try again later.";
		header("Location: ../views/threadcreate.php?forum=$forum");
		die();
	}

	//Add new entry to database
	$table = str_replace(' ', '', strtolower("forum $dbforum comments $threadid"));
	$query = "INSERT INTO $table (poster, timestamp, replyto, text) VALUES ('$poster', '$timestamp', '$replyto', '$text')";
	if (!$db->query($query)) {
		$_SESSION["error"] = "An error occured when submitting data to the database. Please try again.";
		header("Location: ../views/threadview.php?forum=$forum&threadid=$threadid");
		die();
	}
}

header("Location: ../views/threadview.php?forum=$forum&threadid=$threadid");
?>