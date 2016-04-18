<!doctype html>
<html>
<head>
	<title>Thread View</title>
	<meta charset="utf-8" />
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="stylesheet" href="../css/foundation.css" />
	<link rel="stylesheet" href="../css/app.css" />
	<?php include '../php scripts/navigator.php';?>
	<?php include '../php scripts/validforum.php';?>
</head>
<body>
	<?php
	session_start();
	createNavigator(); //Create the navigator at the top of the page. This is defined in navigator.php
	
	//Get which forum and threadid to work with
	if (!isset($_GET["forum"])) {
		echo "<p id='does-not-exist-text'>Sorry but the specified forum does not exist</p>";
		die();
	}
	$forum = $_GET["forum"];
	$forum = urldecode($forum);
	$threadid = $_GET["threadid"];
	
	//Check if forum is an vaild forum (one that is accessable by the forum tab) and obtain permissions
	$foruminfo = validForum($forum);
	if ($foruminfo["valid"] == false) {
		echo "<p id='does-not-exist-text'>Sorry but the specified forum does not exist</p>";
		die();
	}
	
	//Display title
	echo "<p id='forum-view-title'>$forum Forum<p>";
	
	//Initialize thread variables
	$title = "";
	$creator = "";
	$creatorTimestamp = "";
	$lastPoster = "";
	$lastPosterTimestamp = "";
	$replies = 0;
	$views = 0;
	$text = "";
	$db = "";//Database only
	$handle = "";//File system only
	
	//Get thread info
	if ($foruminfo["storage"] == "fs") {
		$file = "../forums/$forum/$threadid" . ".txt";
		$handle = fopen($file, "r");
		$title = fgets($handle);
		$creator = fgets($handle);
		$creatorTimestamp = fgets($handle);
		$lastPoster = fgets($handle);
		$lastPosterTimestamp = fgets($handle);
		$replies = fgets($handle);
		$views = fgets($handle);
		
		while (!feof($handle)) {
			$text .= fgets($handle);
		}
		$text = nl2br($text);
	}
	else {
		//Connect to database
		$db = new mysqli('localhost', 'root', '', 'estock');
		if ($db->connect_error) {
			echo "<p class='error-text'>Connection with database failed. Please try again later.</p>";
			die();
		}
		
		//Get thread info
		$forumLowercase = strtolower($forum);
		$dbforum = str_replace(' ', '', $forumLowercase);
		$query = "SELECT * FROM forum$dbforum WHERE threadid = $threadid";
		$result = $db->query($query);
		if (!$result) {
			echo "<p class='error-text'>Error obtaining thread information. Please try again.</p>";
			die();
		}
		$row = $result->fetch_assoc();
		$title = $row["title"];
		$creator = $row["creator"];
		$creatorTimestamp = $row["creatortimestamp"];
		$lastPoster = $row["lastposter"];
		$lastPosterTimestamp = $row["lastpostertimestamp"];
		$replies = $row["replies"];
		$views = $row["views"];
		$text = $row["text"];
		
		$text = nl2br($text);
	}
	?>
	
	<!--Print thread block-->
	<div class="site-container padless">
		<div class="thread-view-column column1">
			<?php
				echo "<p>$creatorTimestamp</p>";
				echo "<p>$creator</p>";
			?>
		</div>
		<div class="thread-view-column column2">
			<?php
			echo "<h5>$title</h5>";
			echo "<p>$text</p>";
			?>
		</div>
	</div>	
</body>
</html>