<?php
/** A data structure that holds a category of forums and its links
  * It has a title and description and n links that also have a title and description
  * It has a function that will compute its data and display it
  */
class ForumBlock {
	private $titleText;
	private $descriptionText;
	private $linkTitle = array();
	private $linkDescription = array();
	private $linkLocation = array();
	
	public function display() {
		echo 	"<div class='site-container padless'>";
		echo 	"	<p class='forum-title'>$this->titleText</p>";
		echo 	"	<p class='forum-description'>$this->descriptionText</p>";
		for ($i = 0; $i < sizeof($this->linkTitle); $i++) {
			$title = $this->linkTitle[$i];
			$description = $this->linkDescription[$i];
			$link = $this->linkLocation[$i];
			echo"	<div class='forum-link'>";
			echo"		<p class='forum-link-title'><a href='$link'>$title</a></p>";
			echo"		<p class='forum-link-description'>$description</p>";
			echo"	</div>";
		}
		echo 	"</div>";
	}
	
	public function setTitle($title) {
		$this->titleText = $title;
	}
	
	public function setDescription($description) {
		$this->descriptionText = $description;
	}
	
	public function addLink($title, $description) {
		$this->linkTitle[] = $title;
		$this->linkDescription[] = $description;
		$this->linkLocation[] = "forumview.php?forum=" . urldecode($title);
	}
	
	public function setLink($index, $title, $description) {
		$this->linkTitle[$index] = $title;
		$this->linkDescription[$index] = $description;
		$this->linkLocation[$index] = "forumview.php?forum=" . urldecode($title);
	}
}

/** A data structure that holds all the basic data of a thread to be displayed in a list
  * It holds a title, link to the thread, creator's name, created timestamp, last poster, last post timestamp, number of replies and number of views
  * It has a function that will compute its data and display it
  */
class ForumListThread {
	private $title;
	private $linkToThread;
	private $creator;
	private $creatorTimestamp;
	private $lastPoster;
	private $lastPosterTimestamp;
	private $replies;
	private $views;
	
	public function display() {
		echo "	<div class='forum-view-row'>";
		echo "		<div class='forum-view-column column1'>";
		echo "			<div class='forum-view-element'>";
		echo "				<p class='forum-view-thread-link'>";
		echo "					<a href='$this->linkToThread'>$this->title</a>";
		echo "				</p>";
		echo "				<p class='forum-view-thread-link'>";
		echo "					Started by $this->creator on $this->creatorTimestamp";
		echo "				</p>";
		echo "			</div>";
		echo "		</div>";
		echo "		<div class='forum-view-column column2'>";
		echo "			<div class='forum-view-element'>";
		echo "				<p class='forum-view-thread-link'>";
		echo "					$this->lastPoster";
		echo "				</p>";
		echo "				<p class='forum-view-thread-link'>";
		echo "					$this->lastPosterTimestamp";
		echo "				</p>";
		echo "			</div>";
		echo "		</div>";
		echo "		<div class='forum-view-column column3'>";
		echo "			<div class='forum-view-element'>";
		echo "				<p class='forum-view-thread-link'>";
		echo "					$this->replies";
		echo "				</p>";
		echo "				<p class='forum-view-thread-link'>";
		echo "					$this->views";
		echo "				</p>";
		echo "			</div>";
		echo "		</div>";		
		echo "	</div>";
	}
	
	public function setTitle($title) {
		$this->title = $title;
	}
	
	public function setLinkToThread($linkToThread) {
		$this->linkToThread = $linkToThread;
	}
	
	public function setCreator($creator) {
		$this->creator = $creator;
	}
	
	public function setCreatorTimestamp($creatorTimestamp) {
		$this->creatorTimestamp = $creatorTimestamp;
	}
	
	public function setLastPoster($lastPoster) {
		$this->lastPoster = $lastPoster;
	}
	
	public function setLastPosterTimestamp($lastPosterTimestamp) {
		$this->lastPosterTimestamp = $lastPosterTimestamp;
	}
	
	public function setReplies($replies) {
		$this->replies = $replies;
	}
	
	public function setViews($views) {
		$this->views = $views;
	}
}
?>