<?php
/** This function is used to find out 3 things
  * 1. If the forum is a vaild forum. This prevents people form changing the forum varialbe in the url and going to a not acutal forum page
  * 2. The permissions of the forum that determinds who can post a thread to it
  * 3. If the forum information is stored on the file system or database
  */
function validForum($forumname) {
	$foruminfo = array();
	$foruminfo["valid"] = false;
	$foruminfo["permission"] = "";
	$foruminfo["storage"] = "";
	if ($forumname == "News") {
		$foruminfo["valid"] = true;
		$foruminfo["permission"] = "dev";
		$foruminfo["storage"] = "fs";
	}
	else if ($forumname == "From the Developer") {
		$foruminfo["valid"] = true;
		$foruminfo["permission"] = "dev";
		$foruminfo["storage"] = "db";
	}
	else if ($forumname == "Contact Me") {
		$foruminfo["valid"] = true;
		$foruminfo["permission"] = "any";
		$foruminfo["storage"] = "db";
	}
	return $foruminfo;
}
?>