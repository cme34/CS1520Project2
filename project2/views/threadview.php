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
	$storage = $foruminfo["storage"];
	if ($storage == "fs") {
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
		fclose($handle);
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
		$title = rtrim($title);
		$creator = $row["creator"];
		$creator = rtrim($creator);
		$creatorTimestamp = $row["creatortimestamp"];
		$creatorTimestamp = rtrim($creatorTimestamp);
		$lastPoster = $row["lastposter"];
		$lastPoster = rtrim($lastPoster);
		$lastPosterTimestamp = $row["lastpostertimestamp"];
		$lastPosterTimestamp = rtrim($lastPosterTimestamp);
		$replies = $row["replies"];
		$replies = rtrim($replies);
		$views = $row["views"];
		$views = rtrim($views);
		$text = $row["text"];
		$text = rtrim($text);
		
		$text = nl2br($text);
	}
	
	$username = "";
	if (isset($_SESSION["username"])) {
		$username = $_SESSION["username"];
		$username = rtrim($username);
		$creator = rtrim($creator);
	}
	?>
	
	<!--Print thread block-->
	<div class="site-container padless">
		<div class="thread-view-column column1">
			<?php
			echo "<p>$creatorTimestamp</p>";
			echo "<p>$creator</p>";
			//If username equals creator name, then show delete button
			if ($username == $creator) {
				echo "<div id='delete-thread' class='medium secondary button'>Delete Thread</div>";
			}
			echo "<div id='reply-thread' class='medium secondary button'>Reply Thread</div>";
			?>
		</div>
		<div class="thread-view-column column2">
			<?php
			echo "<h5>$title</h5>";
			echo "<p>$text</p>";
			?>
		</div>
	</div>
	
	<!--Comment Section-->
	<div>
		<div id="comments-div">
			<p id="comments-title">Comments</p>
			<?php
			$limit = 1;//How many comments to display at a time
			$commentCountTotal = 0;
			$commentsDisplayed = 0;
			$commentFiles = Array();//File system only
			$commentids = Array();
			$dbforum = str_replace(' ', '', strtolower($forum));
			$tableName = str_replace(' ', '', "forum $dbforum comments $threadid");
				
			//Load comments
			//File system
			$storage = $foruminfo["storage"];
			if ($storage == "fs") {
				//Get comment count
				$dir = "../forums/$forum/$threadid";
				$allFiles = scandir($dir);
				foreach ($allFiles as $a) {
					if (pathinfo($a, PATHINFO_EXTENSION) == "txt" && $a != "commentid.txt") {
						$commentFiles[] = $dir . "/" . $a;
					}
				}
				rsort($commentFiles);//Sort it so that the most recent post is first
				$commentCountTotal = sizeof($commentFiles);
				if ($commentCountTotal < $limit) {
					$limit = $commentCountTotal;
				}
				
				//Create array for Javascript to get the commentids
				for ($i = 0; $i < $commentCountTotal; $i++) {
					//Parse it until it is just a number
					$commentids[] = str_replace(".txt", "", $commentFiles[$i]);
					$commentids[$i] = str_replace("../forums/$forum/$threadid/", "", $commentids[$i]);
				}
				rsort($commentids);
				
				//Display
				while ($commentsDisplayed < $limit) {
					$handle = fopen($commentFiles[$commentsDisplayed], "r");
					$commentid = $commentids[$commentsDisplayed];
					$commentPoster = trim(fgets($handle));
					$commentTimestamp = trim(fgets($handle));
					$commentReplyto = trim(fgets($handle));
					$commentText = "";
					while (!feof($handle)) {
						$commentText .= fgets($handle);
					}
					$commentText = nl2br($commentText);
					fclose($handle);
					
					echo "	<div class='comment' id='comment$commentid'>";
					echo "		<div class='comment-poster'>$commentPoster</div>";
					echo "		<div class='comment-timestamp'>$commentTimestamp</div>";
					echo "		<br /><br />";
					if ($commentReplyto != "") {
						echo "	<div class='comment-replyto'>Reply to: $commentReplyto</div>";
					}
					echo "		<p class='comment-text'>$commentText</p>";
					echo "		<br />";
					echo "		<a href='#' onclick='replyComment($commentid, \"$commentPoster\");'><p class='comment-button' id='comment-button-reply-$commentsDisplayed'>Reply</p></a>";
					if ($username == $commentPoster && $commentText != "This comment was deleted by the user") {
						echo "	<a href='#' onclick='deleteComment($commentid);'><p class='comment-button' id='comment-button-delete-$commentsDisplayed'>Delete</p></a>";
					}
					echo "		<br />";
					echo "	</div>";
					
					$commentsDisplayed++;
				}
			}
			//Database
			else {
				//Connect to database
				$db = new mysqli('localhost', 'root', '', 'estock');
				if ($db->connect_error) {
					echo "<p class='error-text'>Connection with database failed. Please try again later.</p>";
					die();
				}
				
				//Get comment count
				$forumLowercase = strtolower($forum);
				$query = "SELECT * FROM $tableName ORDER BY commentid DESC";
				$result = $db->query($query);
				if (!$result) {
					echo "<p class='error-text'>Error obtaining thread information. Please try again.</p>";
					die();
				}
				$commentCountTotal = $result->num_rows;
				if ($commentCountTotal < $limit) {
					$limit = $commentCountTotal;
				}
				
				while ($row = $result->fetch_assoc()) {
					//Display comments
					if ($commentsDisplayed < $limit) {
						$commentid = $row["commentid"];
						$commentPoster = $row["poster"];
						$commentTimestamp = $row["timestamp"];
						$commentReplyto = $row["replyto"];
						$commentText = $row["text"];
						
						echo "	<div class='comment' id='comment$commentid'>";
						echo "		<div class='comment-poster'>$commentPoster</div>";
						echo "		<div class='comment-timestamp'>$commentTimestamp</div>";
						echo "		<br /><br />";
						if ($commentReplyto != "") {
							echo "	<div class='comment-replyto'>Reply to: $commentReplyto</div>";
						}
						echo "		<p class='comment-text'>$commentText</p>";
						echo "		<br />";
						echo "		<a href='#' onclick='replyComment($commentid, \"$commentPoster\");'><p class='comment-button' id='comment-button-reply-$commentsDisplayed'>Reply</p></a>";
						if ($username == $commentPoster) {
							echo "	<a href='#' onclick='deleteComment($commentid);'><p class='comment-button' id='comment-button-delete-$commentsDisplayed'>Delete</p></a>";
						}
						echo "		<br />";
						echo "	</div>";
						
						$commentsDisplayed++;
					}
					//Add to array for js
					$commentids[] = $row["commentid"];
				}
			}
			?>
		</div>
		<?php
		//Draw show more button, if needed
		if ($commentCountTotal > $limit) {
			echo "<div class='site-container padless' id='show-more'>SHOW MORE</div>";
		}
		else if ($commentCountTotal == 0) {
			echo "<p class='center-text white'>There are no comments to display on this thread</p>";
		}
		?>
	</div>
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
	<script src="../js/myaccount.js"></script>
	<script type="text/javascript">
		var threadid = 0;
		var commentCountTotal = 0;
		var commentDisplayed = 0;
		var limit = 0;
		var forum = "";
		var storage = "";
		var commentids = [];
			
		$(document).on('ready', function() {
			username = "<?php echo $_SESSION["username"] ?>";
			threadid = <?php echo $threadid; ?>;
			commentCountTotal = <?php echo $commentCountTotal; ?>;
			commentDisplayed = <?php echo $commentsDisplayed; ?>;
			limit = <?php echo $limit; ?>;
			forum = "<?php echo $forum; ?>";
			storage = "<?php echo $storage; ?>";
			commentids = <?php echo json_encode($commentids); ?>;
		});
		
		$('#delete-thread').on('click', function() {
			$.ajax({
				url: '../php scripts/deletethread.php', 
				data: {forum: forum, threadid: threadid, storage: storage},
				type: 'get',
				async: false, 
				success: function(result) {
					location = 'forumview.php?forum=' + forum;
				}
			});
		});
		
		$('#reply-thread').on('click', function() {
			if (!$('#comment-form-div').length) {
				$('#comments-div').append(
					'<div class="site-container large" id="comment-form-div">' + 
						'<form id="comment-form" Action="../php scripts/postcomment.php" Method="GET">' + 
							'<input class="text-feild invisible heightless" type="text" name="forum" value="' + forum + '"/>' + 
							'<input class="text-feild invisible heightless" type="text" name="threadid" value="' + threadid + '"/>' + 
							'<input class="text-feild invisible heightless" type="text" name="storage" value="' + storage + '"/>' + 
							'<input class="text-feild invisible heightless" type="text" name="replyto" value=""/>' + 
							'<textarea class="text-feild" type="text" name="text" max-length=4096 />' + 
							'<input class="medium success button" id="comment-submit-button" type="submit" name="submit" value="submit" />' + 
							'<a onclick="removeForm()"><div class="medium secondary button" id="comment-cancel-button">Cancel</div></a>' + 
						'</form>' + 
					'</div>');
			}
			$('#comment-form-div').insertBefore('#comments-div');
		});
		
		$('#show-more').on('click', function() {
			if (commentDisplayed >= commentCountTotal) {
				return
			}
			
			for (i = 0; (i < limit) && (commentDisplayed < commentCountTotal); i++) {
				comment = commentids[(commentDisplayed)];
				//File System
				if (storage == "fs") {
					$.ajax({
						url: '../forums/' + forum + '/' + threadid + '/' + comment + '.txt', 
						async: false, 
						success: getComment
					});
				}
				//Database
				else {
					$.ajax({
						url: '../php scripts/getcommentfromdatabase.php', 
						data: {forum: forum, threadid: threadid, commentid: comment, storage: storage},
						type: 'get',
						async: false, 
						success: getComment
					});
				}
			}
		});
		
		function getComment(result) {
			//Parse result
			result = result.split('\n');
			
			//Get info from post
			poster = result[0];
			timestamp = result[1];
			replyto = result[2];
			text = "";
			for (j = 3; j < result.length; j++) {
				text += result[j];
			}
			
			//Display comment
			usereplyto = "";
			if (replyto != "") {
				usereplyto = '<div class="comment-replyto">Reply to: ' + replyto + '</div>';
			}
			usedeletebutton = "";
			if (username.trim() == poster.trim()) {
				usedeletebutton = '<a href="#" onclick="deleteComment(' + comment + ');"><p class="comment-button" id="comment-button-delete-' + comment + '">Delete</p></a>';
			}
			$('#comments-div').append(
				'<div class="comment" id="comment' + comment + '">' + 
					'<div class="comment-poster">' + poster + '</div>' + 
					'<div class="comment-timestamp">' + timestamp + 
					'</div><br /><br />' + 
					usereplyto + 
					'<p class="comment-text">' + text + '</p>' + 
					'<br />' +
					'<a href="#" onclick="replyComment(' + comment + ', \'' + poster.trim() + '\');"><p class="comment-button id="comment-button-reply-' + comment + '">Reply</p></a>' + 
					usedeletebutton + 
					'<br />' + 
				'</div>');
			
			//Increase threads displayed
			commentDisplayed++;
			
			//If no more news to display, remove button
			if (commentDisplayed >= commentCountTotal) {
				$('#show-more').remove();
			}
		}
		
		function replyComment(comment, replyto) {
			$('#comment-form-div').remove();
			if (!$('#comment-form-div').length) {
				$('#comments-div').append(
					'<div class="site-container large" id="comment-form-div">' + 
						'<form id="comment-form" Action="../php scripts/postcomment.php" Method="GET">' + 
							'<input class="text-feild invisible heightless" type="text" name="forum" value="' + forum + '"/>' + 
							'<input class="text-feild invisible heightless" type="text" name="threadid" value="' + threadid + '"/>' + 
							'<input class="text-feild invisible heightless" type="text" name="storage" value="' + storage + '"/>' + 
							'<input class="text-feild invisible heightless" type="text" name="replyto" value="' + replyto + '"/>' + 
							'<textarea class="text-feild" type="text" name="text" max-length=4096 />' + 
							'<input class="medium success button" id="comment-submit-button" type="submit" name="submit" value="submit" />' + 
							'<a onclick="removeForm()"><div class="medium secondary button" id="comment-cancel-button">Cancel</div></a>' + 
						'</form>' + 
					'</div>');
			}
			$('#comment-form-div').insertAfter('#comment' + comment);
		}
		
		function deleteComment(comment) {
			$.ajax({
				url: '../php scripts/deletecomment.php', 
				data: {forum: forum, threadid: threadid, commentid: comment, storage: storage},
				type: 'get',
				async: false, 
				success: function(result) {
					location.reload();
				}
			});
		}
		
		function removeForm() {
			$('#comment-form-div').remove();
		}
	</script>
</body>
</html>