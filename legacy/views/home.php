<!doctype html>
<html>
<head>
	<title>Home</title>
	<meta charset="utf-8" />
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="stylesheet" href="../css/foundation.css" />
	<link rel="stylesheet" href="../css/app.css" />
	<?php include '../php scripts/navigator.php';?>
</head>
<body>
	<?php
	session_start();
	createNavigator(); //Create the navigator at the top of the page. This is defined in navigator.php
	
	//Display title
	echo "<h1 id='home-page-title'>A MORTAL AMONG GODS</h1>";
	echo "<h6 id='home-page-sub-title'>A game created by Cory Estock</h6>";
	
	//Obtain all news
	$threadFiles = array();
	$dir = "../forums/News";
	$allFiles = scandir($dir);
	foreach ($allFiles as $a) {
		if (pathinfo($a, PATHINFO_EXTENSION) == "txt" && $a != "threadid.txt") {
			$threadFiles[] = $dir . "/" . $a;
		}
	}
	rsort($threadFiles);//Sort it so that the most recent post is first
	$threadCountTotal = sizeof($threadFiles);
	
	//Display up to the 10 most recent
	$reverser = false;//This is used to flip-flop what side of the news block the image will display on
	$limit = 10;
	if ($threadCountTotal < 10) {
		$limit = $threadCountTotal;
	}
	for ($i = 0; $i < $limit; $i++) {
		$file = $threadFiles[$i];
		$handle = fopen($file, "r");
		//Read though some data that is not need for this
		for ($j = 0; $j < 7; $j++) {
			fgets($handle);
		}
		
		//Get post text
		$text = "";
		while (!feof($handle)) {
			$text .= fgets($handle);
		}
		$text = nl2br($text);
		
		//Find associated image
		$threadid = pathinfo($file, PATHINFO_FILENAME);
		$threadImageExt = "";
		if (file_exists($dir . "/" . $threadid . ".png")) {
			$threadImageExt = ".png";
		}
		else if (file_exists($dir . "/" . $threadid . ".jpg")) {
			$threadImageExt = ".jpg";
		}
		else if (file_exists($dir . "/" . $threadid . ".gif")) {
			$threadImageExt = ".gif";
		}
		$threadImage = $dir . "/" . $threadid . $threadImageExt;
		
		//Create link
		$link = "threadview.php?forum=News&threadid=$threadid";
		
		//Display
		echo "	<div class='site-container large'>";
		echo "		<div class='row'>";
		echo "			<img class='news-image' src='$threadImage' alt='$threadImage'>";
		echo "			<p>$text</p>";	
		echo "		</div>";
		echo "		<div class='row'>";
		echo "			<p class='news-to-forum'><a href='$link'>Go to Forums</a></p>";
		echo "		</div>";
		echo "	</div>";
		
		fclose($handle);
		$reverser = !$reverser;
	}
	?>
</body>
</html>