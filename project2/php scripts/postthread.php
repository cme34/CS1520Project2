<?php
session_start();

//Obtain forum data
$title = $_GET["title"];
$text = $_GET["text"];
$forum = $_GET["Forum"];
$forumLowercase = strtolower($forum);
$username = $_SESSION["username"];
date_default_timezone_set("EST");
$timestamp = date("m-d-Y h:ia e");

//Special case for News Forum
$imagePath = "";
$imageType = "";
$image = "";
if ($forumLowercase == "news") {
	$imagePath = $_GET["image"];
	$imageType = exif_imagetype($imagePath);
	if ($imageType == IMAGETYPE_JPEG) {
		$image = imagecreatefromjpeg($imagePath);
	}
	else if ($imageType == IMAGETYPE_PNG) {
		$image = imagecreatefrompng($imagePath);
	}
	else if ($imageType == IMAGETYPE_GIF) {
		$image = imagecreatefromgif($imagePath);
	}
	else {
		$_SESSION["error"] = "Image is not a valid format or the path is incorrect.";
		header("Location: ../views/threadcreate.php?forum=$forum");
		die();
	}
}

//Determine whether to use file system or database
$storage = $_GET["Storage"];
if ($storage == "fs") {
	//Get thread id
	$threadid = 0;
	$file = "../forums/$forum/threadid.txt";
	if (file_exists($file)) {
		$handle = fopen($file, "r");
		$threadid = fgets($handle);
		fclose($handle);
	}
	$threadid++;
	$handle = fopen($file, "w");
	fwrite($handle, $threadid);
	fclose($handle);
	//Create thread file
	$file = "../forums/$forum/$threadid.txt";
	$handle = fopen($file, "w");
	fwrite($handle, "$title\r\n");
	fwrite($handle, "$username\r\n");
	fwrite($handle, "$timestamp\r\n");
	fwrite($handle, "$username\r\n");
	fwrite($handle, "$timestamp\r\n");
	fwrite($handle, "0\r\n");
	fwrite($handle, "0\r\n");
	fwrite($handle, $text);
	fclose($handle);
	//Special case for News Forum
	if ($forumLowercase == "news") {
		if ($imageType == IMAGETYPE_JPEG) {
			imagejpeg($image, "../forums/$forum/$threadid.png");
		}
		else if ($imageType == IMAGETYPE_PNG) {
			imagepng($image, "../forums/$forum/$threadid.png");
		}
		else if ($imageType == IMAGETYPE_GIF) {
			imagegif($image, "../forums/$forum/$threadid.png");
		}
	}
	//Make Comments directory
	mkdir("../forums/$forum/$threadid");
	$handle = fopen("../forums/$forum/$threadid/commentID.txt", "r");
	fwrite($handle, "0");
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
	
	//Sterilize Inputs
	$title = mysql_real_escape_string($title);
	$text = mysql_real_escape_string($text);
	$username = mysql_real_escape_string($username);
	$timestamp = mysql_real_escape_string($timestamp);
	
	//Add new entry to database
	$dbforum = str_replace(' ', '', $forumLowercase);
	$query = "INSERT INTO forum$dbforum (title, text, creator, creatortimestamp, lastposter, lastpostertimestamp, replies, views) VALUES ('$title', '$text', '$username', '$timestamp', '$username', '$timestamp', 0, 0)";
	if (!$db->query($query)) {
		$_SESSION["error"] = "An error occured when submitting data to the database. Please try again.";
		header("Location: ../views/threadcreate.php?forum=$forum");
		die();
	}
	
	//Get thread id
	$query = "SELECT threadid FROM forum$dbforum ORDER BY threadid DESC LIMIT 1;";
	$result = $db->query($query);
	if (!$result) {
		$_SESSION["error"] = "An error occured when submitting data to the database. Please try again.";
		header("Location: ../views/threadcreate.php?forum=$forum");
		die();
	}
	else {
		$threadid = $result->fetch_assoc()["threadid"];
	}
	
	//Add comments table
	$tablename = "forum $dbforum comments $threadid";
	$tablename = str_replace(' ', '', $tablename);
	$query = "CREATE TABLE $tablename (commentid INT(11) AUTO_INCREMENT, poster VARCHAR(64) NOT NULL, timestamp VARCHAR(64) NOT NULL, replyto VARCHAR(64) NOT NULL, text VARCHAR(1280), PRIMARY KEY (commentid))";
	if (!$db->query($query)) {
		$_SESSION["error"] = "An error occured when submitting data to the database. Please try again.";
		header("Location: ../views/threadcreate.php?forum=$forum");
		die();
	}
}

header("Location: ../views/forumview.php?forum=$forum");
?>