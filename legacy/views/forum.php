<!doctype html>
<html>
<head>
	<title>Forum</title>
	<meta charset="utf-8" />
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="stylesheet" href="../css/foundation.css" />
	<link rel="stylesheet" href="../css/app.css" />
	<?php include '../php scripts/navigator.php';?>
	<?php include '../php scripts/forumclasses.php';?>
</head>
<body>
	<?php
	session_start();
	createNavigator(); //Create the navigator at the top of the page. This is defined in navigator.php
	
	//Create forum blocks
	$newsBlock = new ForumBlock(); //ForumBlock is defined in forumclasses
	$newsBlock->setTitle("News");
	$newsBlock->setDescription("Updates about or related to the game");
	$newsBlock->addLink("News", "The news on the home page, but in a more organized fasion");
	$newsBlock->display();
	
	$devBlock = new ForumBlock();
	$devBlock->setTitle("Developer");
	$devBlock->setDescription("Forum pages related to the developer");
	$devBlock->addLink("From the Developer", "Posts from the developer that are not news");
	$devBlock->addLink("Contact Me", "Ask the developer questions here");
	$devBlock->display();
	?>	
</body>
</html>