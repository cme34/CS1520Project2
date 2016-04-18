<!doctype html>
<html>
<head>
	<title>About The Developer</title>
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
	?>
	
	<!--Since this page is mostly just informational, the rest of this is just HTML-->
	<h1 id="home-page-title">About The Developer</h1>
	<div class="site-container large">
		<div class="row">
			<div class="news-image reverse">
				<img class="about-me-image right" src='../img/Photo 1.jpg' alt=''>
			</div>
			<h5>The Early Days</h5>
			<p class="about-me-text">
				&emsp;&emsp; Hello! My name is Cory Estock. I was born on May 14, 1994. I was born and rasied in the township of Pennhills in Pittsburgh, 
				Pennsylvania. Ever since I was young, video games have had a large impact on my life. Most of my early youth was watching my 
				older brother play video games until I was capable of playing them myself. I still remember the first game I ever played was 
				<u>The Legend of Zelda</u>. Ever since that day, my fate was sealed and I knew what I wanted to do with my life.
				<br/>
				&emsp;&emsp; As I grew older, I started to doubt I would ever be able to do what I planned as a child. Once I got to high school, I began 
				looking into computer aided drafting as a career choice. However, as my brother who also pursued that career path had trouble finding
				a job, I decided to look into something else. So, in my senior year of high school, despite being told it was very difficult, I decided 
				to take a class in computer programming.
			</p>
		</div>
		<br/>
		<div class="row">
			<div class="news-image">
				<img class="about-me-image left" src='../img/Photo 2.jpg' alt=''>
			</div>
			<h5>Programming</h5>
			<p class="about-me-text">
				&emsp;&emsp; Despite programming being a fairly difficult concept to grasp, it came to me with ease. In my class in high school, I would complete 
				projects that were meant to take weeks in just a few days. This also gave me a lot of free time in the class to mess around and try things 
				that allowed me to learn more than what was just part of the class. My teacher was very supportive of my success in his class. He would give 
				me extra challeging assignments to further help hone my skills.
				</br>
				&emsp;&emsp; After high school, I decided to pursue a career in computer science. I went to the Community College of Allegeny County for two years 
				and got an Associates Degree in Computer Scienece and Information Technology. After that, I transfered to The University of Pittsburgh to 
				further my education and get my Bachelors. It was during my first year at The University of Pittsburgh that I decided to make a 
				game.
			</p>
		</div>
		<br/>
		<div class="row">
			<div class="news-image reverse">
				<img class="about-me-image right" src='../img/Photo 3.jpg' alt=''>
			</div>
			<h5>A Mortal Among Gods</h5>
			<p class="about-me-text">
				&emsp;&emsp; I began development on <u>A Mortal Among Gods</u> on June 28, 2015. My plan with the game was to pay tribute to the games of 
				my childhood, as well as some of my favorite games. The game is a story driven action platformer that is inspired by classic game series such as 
				Castlevania, Metroid and Megaman. The game conceptually is also inspired by a lot of games, movies, and shows that I like as well.
				<br/>
				&emsp;&emsp; I am	working on the game as a solo project, but to do something cool, I decided to let some of my closer friends create a 
				character for the game. I choose to do this so that my friends could have a part in the universe I created. The game is planned to 
				be released by the end of the year in 2016.
			</p>
		</div>
		<div class="row">
			<div class="news-image">
				<img class="about-me-image left" src='../img/Photo 4.jpg' alt=''>
			</div>
			<h5>Hobbies</h5>
			<p class="about-me-text">
				&emsp;&emsp; Video games are not the only big thing in my life. Rock and metal music has been something that I have loved for a long time. When 
				I was in high school, I took up guitar playing. I still play to this day and plan on being part of a band with my cousin called The Housemen
				of the Apocalypse, where I will be Famine.
				<br/>
				&emsp;&emsp; Back on the topic of video games, I am dedicated League of Legends player. I play the game daily with my team and have been doing so 
				since Fall of 2013. My prefered position is mid lane, and my favorite five champions to play are Ahri, Syndra, Zyra, Anivia, and LeBlanc.
			</p>
		</div>
	</div>
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
	<script src="../js/myaccount.js"></script>
</body>
</html>