<!DOCTYPE html>

<html lang="en-us">

<head>
	<meta charset="utf-8">
	<!-- Link documents to style sheet and JS code -->
	<link rel="stylesheet" href="../css/masterPage.css" type="text/css">
	<link rel="stylesheet" href="../css/menu.css" type="text/css">
	<link rel="stylesheet" href="../css/backgroundVideo.css" type="text/css">
	<link rel="stylesheet" href="../css/ProfileContainer.css" type="text/css">
	<link rel="stylesheet" href="../css/updateStatus.css" type="text/css">
	<link rel="stylesheet" href="../css/breweryPage.css" type="text/css">
	<!--<link rel="stylesheet" href="../css/address.css" type="text/css">
	<link rel="stylesheet" href="../css/hours.css" type="text/css">
	<link rel="stylesheet" href="../css/message.css" type="text/css">-->

  <script src="../js/NewsFeedBuilder.js"></script>
	<script src="../js/contentSwitch.js"></script>

	<!-- Set title of page -->
	<title>Brewery Page</title>


	<!-- Analytics Script -->
	<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-83948702-3', 'auto');
  ga('send', 'pageview');

</script>


	<!-- For the background image -->
	<div class="is_overlay">
		<img src="http://beerhopper.me/img/bckImg.jpg" alt="Background img">
	</div>

	

</head>

<?php
  	//Start the session 
	  session_start();

	  //Get the token to prove the user was logged in 
	  if(strlen($_SESSION['loginToken']) == 0){
		  //redirect to the login page 
		  header("Location: ../index.php");
	  }else{
		  //echo "<p style=\"color:white\">You rock: " . $_GET['id'] . "<br></p>";
	  }

	  	//Connect to the DB
	 	$connection = mysqli_connect("localhost", "goule001", "goule001", "team3");

		//Check the connection
    		if(mysqli_connect_errno()){
       		die("Connection Failed. ERR: " . mysqli_connect_error());
    		}

	  //Start the SQL Query to get the brewery information
	  $getBreweryInfoQuery = "SELECT BreweryName, ProfilePicURL, CoverPicURL, CONCAT(l.City, ', ', l.State) AS City FROM BreweryTable b, BreweryLocation l WHERE b.breweryID = l.breweryID AND b.breweryID=" . $_GET['id'];
	  $getBreweryInnfoResults = mysqli_query($connection, $getBreweryInfoQuery);

	  //Check to see if the brewery exists, should only be one result
	  if($getBreweryInnfoResults-> num_rows > 0){
		  //If the brewery exists, get the info 
		  while($row = mysqli_fetch_assoc($getBreweryInnfoResults)){
			//echo "<p style=\"color:white;\">Hello World</p>";

			  //Save the values
			  $BreweryName = $row['BreweryName'];
			  $ProfilePicURL = $row['ProfilePicURL'];
			  $CoverPicURL = $row['CoverPicURL'];
			  $City = $row['City'];

			  //If the cover pic does not exist, set it to the 
		  }
	  }else{
		  //DNE Exist (Show page not found)
		  header("Location: ./PageNotFound.html?breweryID=" . $_GET['id']);
	  }

	
  ?>

<body>
	<!-- onload="buildFeed();" -->
	<!-- Header for Logo -->
	<header class="header">
		<a href="../html/profilePage.php">
			<img class="logo" id="logo" alt="Beer Hopper Logo" src="../img/Beer_Hopper_Banner.png?raw=true">
		</a>

	</header>

	<!-- Navigation Panel; to the right of the logo -->
	<nav>
		<table class="menu" title="Menu">
			<tbody>
				<tr>
					<!-- Main Profile Page -->
					<th class="menuItem">
						<input type="image" id="homeBtn" src="../img/House.png?raw=true" class="navBtn" onclick="goHomeAnd('BrewerySignUp.html')"
							alt="home">
					</th>
					<th>|</th>

					<!-- Settings -->
					<th class="menuItem">
						<input type="image" id="settingsBtn" src="../img/gear.png?raw=true" class="navBtn" onclick="goHomeAnd('settings.html')" alt="home">
					</th>
				</tr>
			</tbody>
		</table>
	</nav>

	<!--Left side bar; will be profile information -->
	<aside class="left">

		<div id="profileContainer">
			<!-- Three items will appear here... Pic, Name and Edit Button -->
			<img class="profileImg" id="profileImg" src="<?php echo $ProfilePicURL; ?>" alt="<?php echo $BreweryName; ?>" onclick="showSRC('editProfilePicture.html')">
			<p class="profileName" onclick="showSRC('editBreweryName.html')"><?php echo $BreweryName; ?><br></p>
			<p class="breweryLocation"><?php echo $City;?>
				<br></p>
		</div>

		<div class="subsection">
			<!-- About Section -->
			<div class="stdSection about" id="aboutSection">
				<div class="stdSectionTitle">
					About
				</div>
				<div class="table">
					<div class="smalltableCell">
						<a onclick="showSRC('hours.html')">
							<!-- hours -->
							<div class="tableCell img">
								<img class="smalltableCell" src="../img/time.png?raw=true" alt="Hours Icon">
							</div>
							<div class="smalltableCell title">
								Hours
							</div>
						</a>
					</div>
					<div class="smalltableCell">
						<a onclick="showSRC('EmptyForm.html')">
							<div class="tableCell img">
								<img class="smalltableCell" src="../img/story.png?raw=true" alt="Story Icon">
							</div>
							<div class="smalltableCell title">
								Story
							</div>
						</a>
					</div>
					<div class="smalltableCell">
						<a onclick="showSRC('address.html')">
							<!-- address -->
							<div class="tableCell img">
								<img class="smalltableCell" src="../img/location.png?raw=true" alt="Address Icon">
							</div>
							<div class="smalltableCell title">
								Address
							</div>
						</a>
					</div>
					<div class="smalltableCell">
						<a href="BeerList.html" onclick="showSRC('BeerList.html');return false;resizeIframeBeerList(this);">
							<div class="tableCell img">
								<img class="smalltableCell" src="../img/Beer.png?raw=true" alt="Beers Icon">
							</div>
							<div class="smalltableCell title">
								Beers
							</div>
						</a>
					</div>
					<div class="smalltableCell">
						<a onclick="showSRC('FollowingPage.html')">
							<div class="tableCell img">
								<img class="smalltableCell" src="../img/Follow.png?raw=true" alt="Follow Icon">
							</div>
							<div class="smalltableCell title">
								Follow
							</div>
						</a>
					</div>
					<div class="smalltableCell">
						<a onclick="showSRC('message.html')">
							<!-- message -->
							<div class="tableCell img">
								<img class="smalltableCell" src="../img/message.png?raw=true" alt="Message">
							</div>
							<div class="smalltableCell title">
								Message
							</div>
						</a>
					</div>
				</div>
			</div>

			<!-- Followers Section -->
			<div class="stdSection FollowingBrewery" id="followersOfBreweries">
				<div class="stdSectionTitle">
					Followers
				</div>
				<div class="table">
					<div class="smalltableCell">
						<a href="../html/profilePage.php">
							<div class="tableCell img">
								<img class="smalltableCell" src="https://avatars1.githubusercontent.com/u/14881167?v=3&s=466" alt="Mikal Image">
							</div>
							<div class="smalltableCell title">
								Mikal Callahan
							</div>
						</a>
					</div>
					<div class="smalltableCell">
						<a href="../html/profilePage.php">
							<div class="tableCell img">
								<img class="smalltableCell" src="https://avatars1.githubusercontent.com/u/13024523?v=3&s=466" alt="Justin Image">
							</div>
							<div class="smalltableCell title">
								Justin Goulet
							</div>
						</a>
					</div>

					<div class="smalltableCell">
						<a href="../html/profilePage.php">
							<div class="tableCell img">
								<img class="smalltableCell" src="https://media.licdn.com/mpr/mpr/shrinknp_400_400/AAEAAQAAAAAAAASzAAAAJDY4NTJhYjhiLWUzOGQtNDVmZi1hMjFkLTc4MGJjMTUzNjFkYw.jpg"
									alt="Myles Image">
							</div>
							<div class="smalltableCell title">
								Myles Merrill
							</div>
						</a>
					</div>
				</div>
				<div class="stdSectionFooter">
					<a onclick="showSRC('FollowingPage.html')" class="moreClicked">more</a>
				</div>
			</div>
		</div>
	</aside>

	<!-- Right Side bar; will be used for side navigation panels -->
	<aside class="right">
		<div class="subsection">
			<div class="stdSection FollowingBreweries" id="followingBreweries">
				<div class="stdSectionTitle">
					Following
				</div>
				<div class="table">
					<div class="smalltableCell">
						<a href="../html/breweryPage.html">
							<div class="tableCell img">
								<img class="smalltableCell" src="http://brewbound-images.s3.amazonaws.com/wp-content/uploads/2013/03/ballast-point.jpg" alt="Ballast Image">
							</div>
							<div class="smalltableCell title">
								Ballast Point
							</div>
						</a>
					</div>
					<div class="smalltableCell">
						<a href="../html/breweryPage.html">
							<div class="tableCell img">
								<img class="smalltableCell" src="http://www.benekeith.com/images/beverage/Green_Flash_2014_Logo_400x400.png" alt="Green Flash Image">
							</div>
							<div class="smalltableCell title">
								Green Flash
							</div>
						</a>
					</div>

					<div class="smalltableCell">
						<a href="../html/breweryPage.html">
							<div class="tableCell img">
								<img class="smalltableCell" src="http://tapthatkegnow.com/wp-content/uploads/acousticlogo2.jpg" alt="Acoustic Ales Image">
							</div>
							<div class="smalltableCell title">
								Acoustic Ales
							</div>
						</a>
					</div>
				</div>
				<div class="stdSectionFooter">
					<a onclick="showSRC('FollowingPage.html')" class="moreClicked">more</a>
				</div>
			</div>

			<!-- Highest Rated -->
			<div class="stdSection main" id="highestRated">
				<div class="stdSectionTitle">
					Highest Rated
				</div>
				<div class="table">
					<div class="smalltableCell">
						<a onclick="showSRC('BeerInfo.php')">
							<div class="tableCell img">
								<img class="smalltableCell" src="http://indianjoebrewing.com/wp-content/uploads/2013/08/081.png" alt="Red Ale Image">
							</div>
							<div class="smalltableCell title">
								Red Ale
							</div>
						</a>
					</div>
					<div class="smalltableCell">
						<a onclick="showSRC('BeerInfo.php')">
							<div class="tableCell img">
								<img class="smalltableCell" src="http://indianjoebrewing.com/wp-content/uploads/2013/08/06.png" alt="Peach Ale Image">
							</div>
							<div class="smalltableCell title">
								Peach Ale
							</div>
						</a>
					</div>

					<div class="smalltableCell">
						<a onclick="showSRC('BeerInfo.php')">
							<div class="tableCell img">
								<img class="smalltableCell" src="http://indianjoebrewing.com/wp-content/uploads/2013/08/083-162x300.png" alt="Hazlenut Porter Image">
							</div>
							<div class="smalltableCell title">
								Hazelnut Porter
							</div>
						</a>
					</div>
				</div>
				<div class="stdSectionFooter">
					<a onclick="showSRC('BeerList.html')" class="moreClicked">more</a>
				</div>
			</div>

			<!-- Not yet implemented - Calendar -->
			<div class="stdSection Calendar" id="calendar">
				<div class="stdSectionTitle">
					Calendar
				</div>
			</div>
		</div>
	</aside>

	<!-- Main content area -->
	<section class="breweryPage">
		<!-- Display the brewery's cover image -->
		<div>
			<img alt="Brewery Cover Image" id="coverImage" src="<?php echo $CoverPicURL; ?>" onclick="showSRC('editCoverPicture.html')">
		</div>
		<!-- News Feed -->
		<!--<div class="newsFeedHeader breweryPage">
		</div> -->

		<div class="breweryPage newsFeed">
			<!-- For example purposes, add the add brewery panel -->
			<iframe id="contentFrame" src="../html/NewsFeed.html" style="min-width:480px" title="subframe" onload="resizeIframe(this);"></iframe>
			<div class="newsFeed" id="MainArea"></div>

		</div>

	</section>

	<!-- Footer information; additional links etc -->

</body>

</html>
