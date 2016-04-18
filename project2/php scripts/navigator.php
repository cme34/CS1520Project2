<?php
/** This is ment to be called by every view. It creates the navigator (header) for the page.
  * It at allow for the navigator to be consistant on every view and be called in one line.
  */
function createNavigator() {
	$navigator = new Navigator();
	$navigator->addButtonLeft(new NavigatorButton("Home", "home.php"));
	$navigator->addButtonLeft(new NavigatorButton("About the Developer", "aboutthedeveloper.php"));
	$navigator->addButtonLeft(new NavigatorButton("Forum", "forum.php"));
	if (isset($_SESSION["username"])) {
		$navigator->addButtonRight(new NavigatorButton("Logout", "../php scripts/logout.php")); //logout.php is not a page. It is a script that will log you out and take you to home.php
		$navigator->addButtonRight(new NavigatorButton("My Account", "."));
	}
	else {
		$navigator->addButtonRight(new NavigatorButton("Create Account", "createaccount.php"));
		$navigator->addButtonRight(new NavigatorButton("Login", "login.php"));
	}
	$navigator->display();
}

/** A data structure that holds the information for the navigator (header) that is supposed to be on top of every view.
  * It is split between buttons on the left and buttons on the right.
  * It has a function that will compute its data and display the navigator with it
  */
class Navigator {
	private $naviButtonsLeft = array();
	private $naviButtonsRight = array();
	
	public function addButtonLeft($button) {
		$this->naviButtonsLeft[] = $button;
	}
	
	public function addButtonRight($button) {
		$this->naviButtonsRight[] = $button;
	}
	
	public function display() {
		echo "<div class='navigator-background'>";
		echo "<div class='navigator-left'>";
		foreach ($this->naviButtonsLeft as $button) {
			$text = $button->getButtonText();
			$link = $button->getButtonLink();
			echo "<a href='$link'><div class='medium button navigator-button'>$text</div></a>";
		}
		echo "</div>";
		echo "<div class='navigator-right'>";
		foreach ($this->naviButtonsRight as $button) {
			$text = $button->getButtonText();
			$link = $button->getButtonLink();
			//This takes the text and creates a nice css id for the button
			$id = strtolower($text);
			$id = str_replace(' ', '-', $id);
			if ($link == "" || $link == ".") { 
				echo "<div class='medium button navigator-button' id='$id'>$text</div>";
			}
			else {
				echo "<a href='$link'><div class='medium button navigator-button' id='$id'>$text</div></a>";
			}
		}
		echo "</div>";
		echo "</div>";
		//Create my account window
		echo "	<div id='myaccount'>";
		echo "		<a href='changepassword.php'><p id='change-password-button'>Change Password</p></a>";
		echo "		<a href='changeemail.php'><p id='change-email-button'>Change Email</p></a>";
		echo "		<a href='../php scripts/logout.php'><p>Logout</p></a>";
		echo "	</div>";
		//Create Hello! text
		if (isset($_SESSION["username"])) {
			$username = $_SESSION["username"];
			echo "<p id='hello-username'>Hello $username!</p>";
		}
		else {
			echo "<p id='hello-username'>Hello Guest!</p>";
		}
	}
}

/** This holds the info for a single button of the Navigator.
  * It holds the button text and its link
  */
class NavigatorButton {
	private $buttontext;
	private $buttonlink;
	
	function __construct($text, $link) {
		$this->buttontext = $text;
		$this->buttonlink = $link;
	}
	
	public function setButtonText($text) {
		$this->buttontext = $text;
	}
	
	public function setButtonLink($link) {
		$this->buttonlink = $link;
	}
	
	public function getButtonText() {
		return $this->buttontext;
	}
	
	public function getButtonLink() {
		return $this->buttonlink;
	}
}
?>