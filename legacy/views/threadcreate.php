<!doctype html>
<html>
<head>
	<title>Thread Create</title>
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
	
	//Get which forum to work with
	if (!isset($_GET["forum"])) {
		echo "<p id='does-not-exist-text'>Sorry but the specified forum does not exist</p>";
		die();
	}
	$forum = $_GET["forum"];
	$forum = urldecode($forum);
	
	//Check if forum is an vaild forum (one that is accessable by the forum tab) and obtain permissions
	$foruminfo = validForum($forum);
	if ($foruminfo["valid"] == false) {
		echo "<p id='does-not-exist-text'>Sorry but the specified forum does not exist</p>";
		die();
	}
	
	//Display title
	echo "<p id='forum-view-title'>$forum Forum<p>";
	
	//Check if you have permission to create a thread on this forum
	if (!isset($_SESSION["username"])) {
		echo "<p class='error-text'>You don't have permission to post on this forum</p>";
		die();
	}
	else if ($foruminfo["permission"] == "dev" && $_SESSION["username"] != "Cory Estock") {
		echo "<p class='error-text'>You don't have permission to post on this forum</p>";
		die();
	}
	?>
	<div class="site-container large">
		<h3>Create Thread</h3>
		<form Action="../php scripts/postThread.php" Method="GET">
			Title: <input class="text-feild" name="Title" type="text" maxlength=128></input>
			<p class="character-limit-text">Character Limit: 128</p>
			<?php
			//Special case for News Forum
			if ($forum == "News") {
				echo "Image: <input class='text-feild' name='Image' type='text'></input>";
				echo "<p class='character-limit-text'>Supported Formats: png, jpg and gif</p>";
			}
			?>
			Text: <textarea class="text-feild" rows=8 name="Text" type="text" maxlength=4096></textarea>
			<p class="character-limit-text">Character Limit: 4096</p>
			<?php
			//This input is hidden, it is to allow the createThread script to obtain the forum
			echo "<input class='text-feild invisible' name='Forum' type='text' value='$forum'></input>";
			//This input is hidden, it is to allow the createThread script to obtain where the threads are stored
			$storage = $foruminfo["storage"];
			echo "<input class='text-feild invisible' name='Storage' type='text' value='$storage'></input>";
			?>
			<button class="medium success button" id="post-thread-button">Post Thread</button>
			<?php
			if (isset($_SESSION["error"])) {
				$err = $_SESSION["error"];
				unset($_SESSION["error"]);
				echo "<p class='error-text'>$err</br></p>";
			}
			?>
		</form>
	</div>	
</body>
</html>