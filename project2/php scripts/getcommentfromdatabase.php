<?php
//Obtain get variables
$forum = $_GET['forum'];
$threadid = $_GET['threadid'];
$commentid = $_GET['commentid'];

//Sterilize Inputs
$forum = mysql_real_escape_string(str_replace(' ', '', strtolower($forum)));
$threadid = mysql_real_escape_string($threadid);
$commentid = mysql_real_escape_string($commentid);

//Connect to database
$db = new mysqli('localhost', 'root', '', 'estock');
if ($db->connect_error) {
	echo "Connection with database failed. Please try again later.";
	die();
}

//Get comment
$table = str_replace(' ', '', "forum $forum comments $threadid");
$query = "SELECT * FROM $table WHERE commentid = $commentid";
$result = $db->query($query);
if (!$result) {
	echo "An error occured when submitting data to the database. Please try again.";
	die();
}

//Return comment
$row = $result->fetch_assoc();
$return = $row["poster"] . "\n" . $row["timestamp"] . "\n" . $row["replyto"] . "\n" . $row["text"];
echo $return;
?>