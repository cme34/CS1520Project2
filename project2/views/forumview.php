<!doctype html>
<html>
<head>
	<title>Forum View</title>
	<meta charset="utf-8" />
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="stylesheet" href="../css/foundation.css" />
	<link rel="stylesheet" href="../css/app.css" />
	<?php include '../php scripts/navigator.php';?>
	<?php include '../php scripts/forumclasses.php';?>
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
	
	//If a page is defined in the url, then get it
	$currentPage = 1;
	if (isset($_GET["page"])) {
		$currentPage = $_GET["page"];
	}
	
	//Get thread count
	$threadCountTotal = 0;
	$threadFiles = array();//Only used in file system (fs)
	$db;//Only used in database
	if ($foruminfo["storage"] == "fs") {
		$dir = "../forums/$forum";
		$allFiles = scandir($dir);
		foreach ($allFiles as $a) {
			if (pathinfo($a, PATHINFO_EXTENSION) == "txt" && $a != "threadid.txt") {
				$threadFiles[] = $dir . "/" . $a;
			}
		}
		rsort($threadFiles);//Sort it so that the most recent post is first
		$threadCountTotal = sizeof($threadFiles);
	}
	else {
		//Connect to database
		$db = new mysqli('localhost', 'root', '', 'estock');
		if ($db->connect_error) {
			echo "<p class='error-text'>Connection with database failed. Please try again later.</p>";
			die();
		}
		
		//Get thread count
		$forumLowercase = strtolower($forum);
		$dbforum = str_replace(' ', '', $forumLowercase);
		$query = "SELECT * FROM forum$dbforum";
		$result = $db->query($query);
		if (!$result) {
			echo "<p class='error-text'>Error obtaining thread information. Please try again.</p>";
			die();
		}
		$threadCountTotal = $result->num_rows;
	}
	
	//Calculate thread count information
	$lastPage = ceil($threadCountTotal / 25);
	if ($lastPage == 0) {
		$lastPage = 1;
	}
	if ($currentPage > $lastPage) {
		echo "<p id='does-not-exist-text'>Sorry but the specified page does not exist</p>";
		die();
	}
	$threadCountLower = ($currentPage - 1) * 25 + 1;
	$threadCountUpper = $threadCountLower + 24;
	if ($threadCountUpper > $threadCountTotal) {
		$threadCountUpper = $threadCountTotal;
	}
	$threadCountAmountShown = $threadCountUpper - $threadCountLower + 1;
	
	//Display some thread count information
	echo "<div class='row'></div>";
	//Determine whether or not to display a create thread button or display text saying they don't have permission to create a thread
	if (!isset($_SESSION["username"])) {
		echo "<p id='forum-permission-text'>You don't have permission to post on this forum</p>";
	}
	else if ($foruminfo["permission"] == "any") {
		echo "<div id='forum-permission-button'><a href='threadcreate.php?forum=$forum'><div class='button success'>Create New Thread</div></a></div>";
	}
	else if ($foruminfo["permission"] == "dev") {
		if ($_SESSION["username"] == "Cory Estock") {
			echo "<div id='forum-permission-button'><a href='threadcreate.php?forum=$forum'><div class='button success'>Create New Thread</div></a></div>";
		}
		else {
			echo "<p id='forum-permission-text'>You don't have permission to post on this forum</p>";
		}
	}
	
	echo "	<p id='forum-thread-count'>Viewing $threadCountLower - $threadCountUpper out of $threadCountTotal</p></br>";
	echo "</div>";
	?>
	
	<!--Thread Links-->
	<div class="site-container padless">
		<div class="forum-view-row">
			<div class="forum-view-column column1">
				<p class="forum-title">Title / Starter</p>
			</div>
			<div class="forum-view-column column2">
				<p class="forum-title">Last Poster</p>
			</div>
			<div class="forum-view-column column3">
				<p class="forum-title">Replies / Views</p>
			</div>
		</div>
		<?php
		if ($threadCountTotal == 0) {
			echo "<p class='center-text'>There are no threads posted on this forum.</p>";
		}
		else if ($foruminfo["storage"] == "fs") {
			for ($i = 0; $i < $threadCountAmountShown; $i++) {
				$index = (($currentPage - 1) * 25) + $i;
				$file = $threadFiles[$index];
				$fileThreadid = pathinfo($file, PATHINFO_FILENAME);
				$handle = fopen($file, "r");
				$thread = new ForumListThread();
				$thread->setTitle(fgets($handle));
				$thread->setLinkToThread("threadview.php?forum=$forum&threadid=$fileThreadid");
				$thread->setCreator(fgets($handle));
				$thread->setCreatorTimestamp(fgets($handle));
				$thread->setLastPoster(fgets($handle));
				$thread->setLastPosterTimestamp(fgets($handle));
				$thread->setReplies(fgets($handle));
				$thread->setViews(fgets($handle));
				fclose($handle);
				$thread->display();
			}
		}
		else {
			$forumLowercase = strtolower($forum);
			$dbforum = str_replace(' ', '', $forumLowercase);
			$index = $threadCountTotal - ($currentPage * $threadCountAmountShown);
			$query = "SELECT * FROM forum$dbforum ORDER BY threadid DESC LIMIT $index, $threadCountAmountShown";
			$result = $db->query($query);
			if (!$result) {
				echo "<p class='error-text'>Error obtaining thread information. Please try again.</p>";
				die();
			}
			while ($row = $result->fetch_assoc()) {
				$fileThreadid = $row["threadid"];
				$thread = new ForumListThread();
				$thread->setTitle($row["title"]);
				$thread->setLinkToThread("threadview.php?forum=$forum&threadid=$fileThreadid");
				$thread->setCreator($row["creator"]);
				$thread->setCreatorTimestamp($row["creatortimestamp"]);
				$thread->setLastPoster($row["lastposter"]);
				$thread->setLastPosterTimestamp($row["lastpostertimestamp"]);
				$thread->setReplies($row["replies"]);
				$thread->setViews($row["views"]);
				$thread->display();
			}
		}
		?>
	</div>
	
	<!--Page Navigator-->
	<div class="site-container medium">
		<p class="forum-view-page-navigator">
			<?php
			//First Page
			if ($currentPage != 1) {
				echo "<a href='forumview.php?forum=$forum&page=1'>first&emsp;</a>";
			}
			else {
				echo "first&emsp;";
			}
			//Prev Page
			if ($currentPage != 1) {
				$page = $currentPage - 1;
				echo "<a href='forumview.php?forum=$forum&page=$page'>prev&emsp;</a>";
			}
			else {
				echo "prev&emsp;";
			}
			
			//Display up to 9 direct page links
			if ($lastPage > 9) {
				//This is a little confusing, but it is to make the page navigator work with greater than 9 pages
				$shift = 0;
				if ($currentPage - 4 > 0 && $currentPage + 4 <= $lastPage) {
					$shift = 0;
				}
				else if ($currentPage - 4 <= 0) {
					$shift = 0 - (($currentPage - 4) - 1);
				}
				else if ($currentPage + 4 > $lastPage) {
					$shift = $lastPage - ($currentPage + 4);
				}
				for ($i = ($currentPage - 4) + $shift; $i <= ($currentPage + 4) + $shift; $i++) {
					if ($i == $currentPage) {
						echo "$i&emsp;";
					}
					else {
						echo "<a href='forumview.php?forum=$forum&page=$i'>$i&emsp;</a>";
					}
				}
			}
			else {
				for ($i = 1; $i < $lastPage + 1; $i++) {
					if ($i == $currentPage) {
						echo "$i&emsp;";
					}
					else {
						echo "<a href='forumview.php?forum=$forum&page=$i'>$i&emsp;</a>";
					}
				}
			}
			
			//Next Page
			if ($currentPage != $lastPage) {
				$page = $currentPage + 1;
				echo "<a href='forumview.php?forum=$forum&page=$page'>next&emsp;</a>";
			}
			else {
				echo "next&emsp;";
			}
			//Last Page
			if ($currentPage != $lastPage) {
				echo "<a href='forumview.php?forum=$forum&page=$lastPage'>last</a>";
			}
			else {
				echo "last";
			}
			?>
		</p>
	</div>
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
	<script src="../js/myaccount.js"></script>
</body>
</html>