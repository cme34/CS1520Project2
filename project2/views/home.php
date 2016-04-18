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
	
	//Create array for Javascript to get the postIDs
	$postIDs = Array();
	for ($i = 0; $i < $threadCountTotal; $i++) {
		//Parse it until it is just a number
		$postIDs[] = str_replace(".txt", "", $threadFiles[$i]);
		$postIDs[$i] = str_replace("../forums/News/", "", $postIDs[$i]);
	}
	
	//Display up to the 5 most recent
	echo "<div id='news-div'>";
	$reverser = 0;//This is used to flip-flop what side of the news block the image will display on
	$limit = 1;
	if ($threadCountTotal < $limit) {
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
		if ($reverser == 1) {
			echo "		<img class='news-image reverse' src='$threadImage' alt='$threadImage' />";
		}
		else {
			echo "		<img class='news-image' src='$threadImage' alt='$threadImage' />";
		}
		echo "			<p>$text</p>";	
		echo "		</div>";
		echo "		<div class='row'>";
		echo "			<p class='news-to-forum'><a href='$link'>Go to Forums</a></p>";
		echo "		</div>";
		echo "	</div>";
		
		fclose($handle);
		if ($reverser == 1) {
			$reverser = 0;
		}
		else {
			$reverser = 1;
		}
	}
	echo "</div>";//Close news-div
	
	//Display show more bar if neccessary
	if ($threadCountTotal > $limit) {
		echo "<div class='site-container padless' id='show-more'>SHOW MORE</div>";
	}
	?>
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
	<script src="../js/myaccount.js"></script>
	<script type="text/javascript">
		var threadCountTotal = 0;
		var threadsDisplayed = 0;
		var limit = 0;
		var reverser = 0;
		var postIDs = [];
			
		$(document).on('ready', function() {
			threadCountTotal = <?php echo $threadCountTotal; ?>;
			threadsDisplayed = <?php echo $limit; ?>;
			limit = <?php echo $limit; ?>;
			reverser = <?php echo $reverser; ?>;
			postIDs = <?php echo json_encode($postIDs); ?>;
		});
		
		$('#show-more').on('click', function() {
			if (threadsDisplayed >= threadCountTotal) {
				return
			}
			
			for (i = 0; (i < limit) && (threadsDisplayed < threadCountTotal); i++) {
				thread = postIDs[(threadsDisplayed + i)];
				$.ajax({
					url: '../forums/News/' + thread + '.txt', 
					async: false, 
					success: function(result) {
						//Parse result
						result = result.split('\n');
						
						//Get text from post
						text = "";
						for (j = 7; j < result.length; j++) {
							text += result[j];
						}
						
						//Create link to forum
						link = 'threadview.php?forum=News&threadid=' + thread;
						
						
						//Create News block
						if (reverser == 1) {
							$('#news-div').append('<div class="site-container large">' + '<div class="row">' + 
							'<img class="news-image reverse" src="../forums/News/' + thread + '.png" alt="../forums/News/' + thread + '.png" />' + 
							'<p>' + text + '</p>' + '</div>' + '<div class="row">' + '<p class="news-to-forum"><a href="' + link + 
							'">Go to Forums</a></p>' + '</div>' + '</div>');
						}
						else {
							$('#news-div').append('<div class="site-container large">' + '<div class="row">' + 
							'<img class="news-image" src="../forums/News/' + thread + '.png" alt="../forums/News/' + thread + '.png" />' + 
							'<p>' + text + '</p>' + '</div>' + '<div class="row">' + '<p class="news-to-forum"><a href="' + link + 
							'">Go to Forums</a></p>' + '</div>' + '</div>');
						}
						if (reverser == 1) {
							reverser = 0;
						}
						else {
							reverser = 1;
						}
						
						//Increase threads displayed
						threadsDisplayed++;
						
						//If no more news to display, remove button
						if (threadsDisplayed >= threadCountTotal) {
							$('#show-more').remove();
						}
					}
				});
			}
		});
	</script>
</body>
</html>