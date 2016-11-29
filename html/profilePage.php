<!DOCTYPE html>

<!--
*	@Author:							Justin Goulet
* @Date-Last-Modified:	November 23, 2016
* @Date-Created:				September 1, 2016
*	@Purpose:							To show a particular person's main profile page
*	@Method:							Using the user's login credentials, this page is first loaded
*												with their content. This includes their profile picture,
*												name, and other identifying information, a table of a user's
*												favorite beers, a table of all who is following the current
*												user, and who the user is currently following.
*												When the user views another's profile, they are shown their profile picture,
*												their name, their favorite beers, who is following them, and
*												who they are following. No privacy settings are set yet.
*												Differnt from a signed in users page, the current user have the option to
*												follow the user or sent them a message.
*
-->
<html lang="en-us">
<head>
		<!-- Import CSS Files -->
		<link rel="stylesheet" href="../css/header.css" type="text/css">
		<link rel="stylesheet" href="../css/masterPage.css" type="text/css">
		<link rel="stylesheet" href="../css/menu.css" type="text/css">
		<link rel="stylesheet" href="../css/backgroundVideo.css" type="text/css">
		<link rel="stylesheet" href="../css/ProfileContainer.css" type="text/css">
		<link rel="stylesheet" href="../css/updateStatus.css" type="text/css">
		<link rel="stylesheet" href="../css/calendarview.css" type="text/css">
		<!-- Import JS Files -->
		<script src="../js/contentSwitch.js"></script>
		<script src="../js/calendarview.js"></script>

		<!-- Analytics Script -->
	<script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
	  ga('create', 'UA-83948702-3', 'auto');
	  ga('send', 'pageview');
		<?php
	  	//Start the session
		  session_start();

			//Get the token to prove the user was logged in
		  if(strlen($_SESSION['loginToken']) == 0){
				//redirect to the login page
		 	header("Location: ../index.php");
			}
			else{
				//e	cho "<p>You rock";
			}
			?>
	</script>
		<!-- Navigation Bar -->
		<nav>
			<table class="menu" title="Menu">
				<tbody>
					<tr>
						<!-- Main Profile Page -->
						<th class="menuItem" title="Home">
							<input type="image" id="homeBtn" src="../img/House.png?raw=true" class="navBtn" onclick="javascript:location.href='../index.php'" alt="home">
						</th>
						<th>|</th>
						<!-- Settings -->
						<th class="menuItem" title="Settings">
							<input type="image" id="settingsBtn" src="../img/gear.png?raw=true" class="navBtn" onclick="showSRC('Settings.php')" alt="home">
						</th>
						<th>|</th>
						<!-- Logout Button -->
						<th class="menuItem" title="Logout">
							<input type="image" id="logoutBtn" src="../img/logout.png?raw=true" class="navBtn" onclick="logout()" alt="home">
						</th>
					</tr>
				</tbody>
			</table>

			<!-- Add a search bar in the top left -->
			<form action="return false;" onsubmit="return false;" method="POST" class="searchForm">
				<label class="hidden">Enter Search Terms here </label>
				<input type="text" placeholder="Search" id="searchText" name="query" class="textSearch">
				<label class="hidden"> Search Field </label>
				<input type="image" id="searchBtn" src="../img/location_filled.png?raw=true" class="searchButton" onclick="startSearch()" alt="search">
			</form>
		</nav>

		<!-- Meta data -->
		<title>Profile</title>
		<!-- For the background image -->
		<div class="is_overlay">
			<img src="http://beerhopper.me/img/bckImg.jpg" alt="Background img">
		</div>
</head>
<?php

		//Import needed PHP files
		include "../php/create_table.php";

	 //Create a basic connection
    $connection = include '../php/DBConnectionReturn.php';

$FName = $LName = $PicURL = $CurrentUser = "";

	//Use already provided var
	$CurrentUser = $_SESSION['currentUser'];

//Get the user's information
	$GetUserInformationQuery = "SELECT * FROM Users WHERE Email='" . $CurrentUser . "'";
	$userInfoResults = mysqli_query($connection, $GetUserInformationQuery);

	//Check to see if exists (it should since we already logged in)
	if($userInfoResults-> num_rows > 0){
		while($row = mysqli_fetch_assoc($userInfoResults)){
			$FName = $row["FName"];
			$LName = $row["LName"];
			$PicURL = $row["ProfilePicURL"];
			break; //Only want the first occurance
		}
		//Unset the results
		mysqli_free_result($userInfoResults);

	}else{
		//err
	}
	//echo "<script type=\"text/javascript\">window.alert(\"User Found: " . $CurrentUser . " FNAME: " . $FName . " LNAME: " . $LName . "\");</script>";
?>
<body>

		<!-- Header Bar -->
		<div class="header">
			<img class="logo" src="../img/Beer_Hopper_Banner.png" alt="Beer Hopper Logo">
		</div>

	<!-- Profile Container -->
	<div class="ProfileContainer">
		<!-- Within the container, we have a rounded profile image -->
		<img src="<?php echo $PicURL ?>" alt="Profile Picture" id="profileImg" class="profileImage" onclick="showSRC('editProfilePicture.php')">
		<br>
		<hr>
		<p class="profileName"><?php echo $FName . " " . $LName?></p>
		<a class="editBtn" onclick="showSRC('editName.php')">Edit</a>
	</div>
	<!-- Left Section -->
	<aside class="left">
		<div class="subsection">
			<?php
			if($_SESSION['signedInUser'] != $CurrentUser){
				?>
			<div class="stdSection" id="About">
				<div class="stdSectionTitle">
				Info
				</div>
				<div class="table">
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
		<?php
			}
			?>
			<div class="stdSection" id="bestTastes">
				<div class="stdSectionTitle">
					Best Tastes
				</div>
				<div class="table">

					<!-- Get Favorited beers for this user -->
					<?php
						$getFavoritedBeersQuery = "SELECT DISTINCT u.BeerID, b.PictureURL, b.BeerName FROM UserFavoritesBeer u, Beers b WHERE u.UserEmail='" . $CurrentUser . "' AND b.BeerID=u.BeerID ORDER BY b.BeerName LIMIT 6";
						$favoritedBeersResults = mysqli_query($connection, $getFavoritedBeersQuery);

						//Create a basic clickable table
						if($favoritedBeersResults) createClickableTable($favoritedBeersResults, 'BeerID', 'PictureURL', 'BeerName');

						//Free the results
						if($favoritedBeersResults) mysqli_free_result($favoritedBeersResults);
					?>
				</div>
				<div class="stdSectionFooter">
					<a href="#" onclick="showSRC('BeerInfo.php');return false;" class="moreClicked">more</a>
				</div>
			</div>
		<div class="stdSection" id="eventCalendar">
			<div class="stdSectionTitle">
				Calendar
			</div>
		</div>
		</div>
	</aside>
	<!-- Right Section -->
	<aside class="right">
		<div class="subsection">
			<div class="stdSection" id="followers">
				<div class="stdSectionTitle">
					Following Me
				</div>
				<div class="table">
					<!-- User 'Following me' -->
					<?php
					$getUsersFollowingMeQuery = "SELECT DISTINCT u.UserEmail, u.OtherUserEmail, them.ProfilePicURL, CONCAT(them.`FName`, '<br>', them.`LName`) AS 'Name', them.LName FROM UserFollowsUser u, Users p, Users them WHERE u.OtherUserEmail=p.Email AND them.Email=u.UserEmail AND u.OtherUserEmail='" . $CurrentUser . "'ORDER BY them.LName LIMIT 3";
					$usersFollowingMeResult = mysqli_query($connection, $getUsersFollowingMeQuery);

					//Create a form to change the current page when clicked
					createBasicForm($usersFollowingMeResult, 'UserEmail', 'ProfilePicURL', 'Name', 'user');

					//Free the results
					if($usersFollowingMeResult) mysqli_free_result($usersFollowingMeResult);

					?>
				</div>
				<div class="stdSectionFooter">
					<a onclick="showSRC('FollowingPage.php')" class="moreClicked">more</a>
				</div>
			</div>
			<div class="stdSection" id="followingBreweries">
				<div class="stdSectionTitle">
					Following Breweries
				</div>
				<div class="table">

				<!-- Using PHP, build the table -->
				<?php
					//Session is already started
					//Get the breweryies the user is following, max 3
					$getBreweriesFollowing = "SELECT DISTINCT BreweryName, ProfilePicURL, b.BreweryID, u.UserEmail FROM BreweryTable b, UserFollowsBrewery u WHERE u.BreweryID = b.BreweryID AND u.UserEmail ='" . $CurrentUser . "' GROUP BY u.BreweryID ORDER BY BreweryName LIMIT 3";
					$breweriesFollowingResults = mysqli_query($connection, $getBreweriesFollowing);

					//If the rows are greater than 1, we can use them to build our table. If not, we need to put a notice to the user.
					if($breweriesFollowingResults) createBasicForm($breweriesFollowingResults, 'BreweryID', 'ProfilePicURL', 'BreweryName', 'brewery');

					//Free the results
					if($breweriesFollowingResults) mysqli_free_result($breweriesFollowingResults);

					/** Should work **/
					if($_SERVER['REQUEST_METHOD'] == 'POST'){
								//Close the sql session
							$connection->close();

    						if(isset($_POST['brewery'])){

							  //Navigate to the brewery page iwth the new id
							  echo "<script type=\"text/javascript\"> document.location.href = \"breweryPage.php?id=" . end(array_keys($_POST)) . "\";</script>";

							}else if(isset($_POST['user'])) {
							    $_SESSION['currentUser'] = strtr(end(array_keys($_POST)), array('#-#' => '.'));
									echo "<script type=\"text/javascript\"> document.location.href = \"profilePage.php\";</script>";
						    }

								//Ends the current session
								session_write_close();
					}

				?>
				</div>
				<div class="stdSectionFooter">
					<a onclick="showSRC('PageNotFound.html')" class="moreClicked">more</a>
				</div>
			</div>
			<div class="stdSection" id="followingUsers">
				<div class="stdSectionTitle">
					Following Users
				</div>
				<div class="table">
					<!-- User 'Following me' -->
					<?php
					$getUsersFollowingMeQuery = "	SELECT DISTINCT ufu.OtherUserEmail, u.ProfilePicURL, CONCAT(u.FName, '<br>', u.LName) AS Name, u.LName
					 															FROM UserFollowsUser ufu, Users u
																				WHERE u.Email=ufu.OtherUserEmail AND ufu.UserEmail='" . $_SESSION['currentUser'] . "' ORDER BY u.LName LIMIT 3";
					$usersFollowingMeResult = mysqli_query($connection, $getUsersFollowingMeQuery);

					//Create the table using the data
					if($usersFollowingMeResult) createBasicForm($usersFollowingMeResult, 'OtherUserEmail', 'ProfilePicURL', 'Name', 'user');

					//Free the results
					if($usersFollowingMeResult) mysqli_free_result($usersFollowingMeResult);

					?>
				</div>
				<div class="stdSectionFooter">
					<a onclick="showSRC('FollowingPage.php')" class="moreClicked">more</a>
				</div>
			</div>
		</div>
	</aside>
	<section>

		<?php
			//Get the current signed in users profile PictureURL
			$proPicQ = "SELECT ProfilePicURL FROM Users WHERE Email='" . $_SESSION['signedInUser'] . "'";
			$res = mysqli_query($connection, $proPicQ);

			if($res->num_rows > 0){
				while($row = mysqli_fetch_assoc($res)){
					$PicURL = $row['ProfilePicURL'];
					break;
				}
			}else{
				//Leave the profile pic the same
			}

		 ?>
		<div class="newsFeedHeader">
			Feed:
			<!-- Black Field to Post -->
			<div class="newsFeedBox">
				<img class="feedImg" id="feedImg" src="<?php echo $PicURL ?>" alt="Image in Feed">
				You:
				<hr/>
				<div class="feedTxt">
					<form>
						<textarea id="postBox" wrap="soft" rows="5"></textarea>
						<br/>
					</form>
				</div>
				<button type="submit" onclick="">Post</button>
			</div>
			<div class="newsFeed" id="MainArea">
			<!-- For example purposes, add the add brewery panel -->
			<iframe id="contentFrame" src="../html/NewsFeed.php" title="subcontent" style="width:100%;" onload="resizeIframe(this);"></iframe>
		</div>
		</div>
		<!-- Black Field to Post -->
	</section>
</body>
</html>
