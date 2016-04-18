<?php
session_start();

//Obtain forum data
$forum = $_GET["forum"];
$threadid = $_GET["threadid"];
$commentid = $_GET["commentid"];
$storage = $_GET["storage"];

//Sterilize Inputs
$forum = mysql_real_escape_string($forum);
$threadid = mysql_real_escape_string($threadid);
$commentid = mysql_real_escape_string($commentid);

if ($storage == "fs") {
	//Get comment data except text
	$file = "../forums/$forum/$threadid/$commentid" . ".txt";
	$handle = fopen($file, "r");
	$commentPoster = trim(fgets($handle));
	$commentTimestamp = trim(fgets($handle));
	$commentReplyto = trim(fgets($handle));
	fclose($handle);
	
	//Rewrite comment as deleted
	$handle = fopen($file, "w");
	fwrite($handle, "$commentPoster\r\n");
	fwrite($handle, "$commentTimestamp\r\n");
	fwrite($handle, "$commentReplyto\r\n");
	fwrite($handle, "This comment was deleted by the user");
	fclose($handle);
}
else {
	//Connect to database
	$db = new mysqli('localhost', 'root', '', 'estock');
	if ($db->connect_error) {
		echo "<p class='error-text'>Connection with database failed. Please try again later.</p>";
		die();
	}
	
	//Replace text in comment
	$table = str_replace(' ', '', strtolower("forum $forum comments $threadid"));
	$query = "UPDATE $table SET text='This comment was deleted by the user' WHERE commentid='$commentid'";
	if (!$db->query($query)) {
		echo "<p class='error-text'>Connection with database failed. Please try again later.</p>";
		die();
	}
}
?>