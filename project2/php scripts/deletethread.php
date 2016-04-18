<?php
session_start();

//Obtain forum data
$forum = $_GET["forum"];
$threadid = $_GET["threadid"];
$storage = $_GET["storage"];

//Sterilize Inputs
$forum = mysql_real_escape_string($forum);
$threadid = mysql_real_escape_string($threadid);

if ($storage == "fs") {
	//Remove thread
	$file = "../forums/$forum/$threadid.txt";
	unlink($file);
	
	//Remove comments
	$dir = "../forums/$forum/$threadid";
	$allFiles = glob("$dir/*");
	foreach ($allFiles as $a) {
		if (is_file($a)) {
			unlink($a);
		}
	}
	rmdir($dir);
	
	//Special case for News forum
	if ($forum == "News") {
		$file = "../forums/$forum/$threadid.png";
		unlink($file);
	}
}
else {
	//Connect to database
	$db = new mysqli('localhost', 'root', '', 'estock');
	if ($db->connect_error) {
		echo "<p class='error-text'>Connection with database failed. Please try again later.</p>";
		die();
	}
	
	//Update forum table
	$table = str_replace(' ', '', strtolower("forum $forum"));
	$query = "DELETE FROM $table WHERE threadid = '$threadid'";
	if (!$db->query($query)) {
		echo "<p class='error-text'>Connection with database failed. Please try again later.</p>";
		die();
	}
	
	//Drop comments table
	$table = str_replace(' ', '', strtolower("forum $forum comments $threadid"));
	$query = "DROP TABLE $table";
	if (!$db->query($query)) {
		echo "<p class='error-text'>Connection with database failed. Please try again later.</p>";
		die();
	}
}
?>