<!DOCTYPE html>

<!--
*	@Author:							Justin Goulet
* @Date-Last-Modified:	November 23, 2016
* @Date-Created:				September 1, 2016
*	@Purpose:							To show a particular brewery's main page
*	@Method:							Using the passed in breweryID, from GET, we are
*												going to to load the current brewery information into a
*												pre-laid out page. This includes the brewery's profile picture,
*												cover picture, beer list, followers and list of those that
*												it is following. Users can interact with each element to learn more.
-->

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
	<script src="../js/analytics.js"></script>

	<!-- For the background image -->
	<div class="is_overlay">
		<img src="http://beerhopper.me/img/bckImg.jpg" alt="Background img">
	</div>



</head>

<?php
		//Import needed PHP files
		include "../php/create_table.php";
  	//Start the session
	  session_start();
		$id = $_GET['id'];
	  //Get the token to prove the user was logged in
	  if(strlen($_SESSION['loginToken']) == 0){
		  //redirect to the login page
		  header("Location: ../index.php");
	  }else{
		  //echo "<p style=\"color:white\">You rock: " . $_GET['id'] . "<br></p>";
	  }

	  	//Connect to the DB
	 	$connection = include '../php/DBConnectionReturn.php';

	  //Start the SQL Query to get the brewery information
	  $getBreweryInfoQuery = "SELECT BreweryName, ProfilePicURL, CoverPicURL, CONCAT(l.City, ', ', l.State) AS City FROM BreweryTable b, BreweryLocation l WHERE b.breweryID = l.breweryID AND b.breweryID=" . $_GET['id'];
	  $getBreweryInnfoResults = mysqli_query($connection, $getBreweryInfoQuery);

		//Get current user info
		$signedInUser = $_SESSION['signedInUser'];
		//Get breweries that user is following
		$signedInUserBreweriesQuery = "SELECT * FROM UserFollowsBrewery WHERE UserEmail='" . $signedInUser . "' AND BreweryID=" . $_GET['id'];
		$signedInUserBreweriesResults = mysqli_query($connection, $signedInUserBreweriesQuery);
		//Determine if user is brewery owner
		$getBreweryOwnerQuery = "SELECT UserEmail FROM BreweryOwner WHERE BreweryID=" . $_GET['id'];
		$getBreweryOwnerResults = mysqli_query($connection, $getBreweryOwnerQuery);
		if ($getBreweryOwnerResults-> num_rows > 0){
			//Signed in user is the brewery owner
			$isUserBreweryOwner = true;
		} else
			$isUserBreweryOwner = false;

		//Check to see if user has favorited brewery
		if ($signedInUserBreweriesResults-> num_rows == 0){
			//Is not following
			$following = 'n';
			$followText = "Follow";
			$followingImage = "../img/Follow.png?raw=true";
		} else {
			//Is following
			$following = 'y';
			$followText = "UnFollow";
			$followingImage = "../img/Unfollow_Follow_Color.png?raw=true";
		}
	  //Check to see if the brewery exists, should only be one result
	  if($getBreweryInnfoResults-> num_rows > 0){
		  //If the brewery exists, get the info
		  while($row = mysqli_fetch_assoc($getBreweryInnfoResults)){

			  //Save the values
			  $BreweryName = $row['BreweryName'];
			  $ProfilePicURL = $row['ProfilePicURL'];
			  $CoverPicURL = $row['CoverPicURL'];
			  $City = $row['City'];

				//Get the visits count (from a different table)
				$visitsCountGet = "SELECT DISTINCT uvb.UserEmail, COUNT(*) AS vists FROM UserVisitsBrewery uvb WHERE uvb.BreweryID=" . $_GET['id'] . " GROUP BY uvb.UserEmail";
				$visitsResult = mysqli_query($connection, $visitsCountGet);
				if($visitsResult->num_rows > 0){
					while (mysqli_fetch_assoc($visitsResult)) {
						$Visits++;
					}
				}else{
					$Visits = 0;
				}

				//Get the visits count (from a different table)
				$visitsCountGetTotal = "SELECT COUNT(*) AS visits FROM UserVisitsBrewery";
				$visitsResultTotal = mysqli_query($connection, $visitsCountGetTotal);
				if($visitsResultTotal->num_rows > 0){
					while ($row = mysqli_fetch_assoc($visitsResultTotal)) {
						$VisitsTotal = $row['visits'];
						break;
					}
				}else{
					$VisitsTotal = 0;
				}

			  //If the cover pic does not exist, set it to the default
				if(empty($CoverPicURL)){
					$CoverPicURL = "../img/DefaultCoverImage.png";
				}
		  }

			//Free the results
			mysqli_free_result($getBreweryInnfoResults);
	  }else{
		  //DNE Exist (Show page not found)
		  header("Location: ./PageNotFound.html?breweryID=" . $_GET['id']);
	  }

		//Now, increment the visit count of said brewery
		$UpdateVisits = "INSERT INTO UserVisitsBrewery VALUES(NULL, '" . $signedInUser . "', " . $_GET['id'] .")";
		if(mysqli_query($connection, $UpdateVisits)){
			//Success
		}else{
			echo "Error With Query: " . mysqli_error($connection);
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
			<form action="return false;" onsubmit="return false;" class="searchForm">
				<label class="hidden">Enter Search Terms here </label>
				<input type="text" placeholder="Search" id="searchText" name="query" class="textSearch">
				<label class="hidden"> Search Field </label>
				<input type="image" id="searchBtn" src="../img/location_filled.png?raw=true" class="searchButton" onclick="startSearch()" alt="search">
			</form>
	</nav>

	<!--Left side bar; will be profile information -->
	<aside class="left">

		<div id="profileContainer">
			<!-- Three items will appear here... Pic, Name and Edit Button -->
			<img class="profileImg" id="profileImg" src="<?php echo $ProfilePicURL; ?>" alt="<?php echo $BreweryName; ?>" onclick="showSRC<?php echo "('editBrewProfPic.php?id=$id')";?>">
			<p class="profileName" onclick="showSRC<?php echo "('editBreweryName.php?id=$id')"; ?>"><?php echo $BreweryName; ?><br></p>
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
						<a onclick="showSRC<?php echo "('Hours.php?id=$id')";?>">
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
						<a onclick="showSRC('Story.php?id=<?php echo $_GET['id'];?>')">
							<div class="tableCell img">
								<img class="smalltableCell" src="../img/story.png?raw=true" alt="Story Icon">
							</div>
							<div class="smalltableCell title">
								Story
							</div>
						</a>
					</div>
					<div class="smalltableCell">
						<a onclick="showSRC<?php echo "('Address.php?id=" . $_GET['id'] . "')" ?>">
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
						<a href="BeerList.html" onclick="showSRC('BeerList.php?breweryID=<?php echo $_GET['id']; ?>');return false;resizeIframeBeerList(this);">
							<div class="tableCell img">
								<img class="smalltableCell" src="../img/Beer.png?raw=true" alt="Beers Icon">
							</div>
							<div class="smalltableCell title">
								Beers
							</div>
						</a>
					</div>
					<div class="smalltableCell">
						<form action="" method="POST" name="followBrew" id="followBrewForm">
							<button type="submit" class="defaultSetBtn" name="followBrew">
								<div class="tableCell img">
									<img class="smalltableCell" src="<?php echo $followingImage ?>" alt="<?php echo $followText ?>">
								</div>
								<div class="smalltableCell title"> <?php echo $followText ?> </div>
							</button>
							<input type="hidden" value="" name="<?php echo strtr($_GET['id'], array('.' => '#-#')) ?>">
						</form>
					</div>
				</div>
			</div>
						<!-- Total visit count. Increments on each page visit/refresh -->
						<div class="stdSection Calendar" id="calendar">
							<div class="stdSectionTitle">
								Unique Visits
									<div class="numberOfVisits"><?php echo number_format($Visits); ?></div>
							</div>
							<div class="stdSectionFooter">
									<?php echo $VisitsTotal ?> Total Visits
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
					<?php
						//Build the table
						$GetWhoBreweryIsFollowing = "SELECT DISTINCT b.OtherBreweryID AS BreweryID, ob.ProfilePicURL, ob.BreweryName FROM BreweryFollowsBrewery b, BreweryTable ob WHERE ob.BreweryID = b.OtherBreweryID AND b.BreweryID=" . $_GET['id'] . " LIMIT 6";
						$GetWhoBreweryIsFollowingResults = mysqli_query($connection, $GetWhoBreweryIsFollowing);

						createBasicForm($GetWhoBreweryIsFollowingResults, 'BreweryID', 'ProfilePicURL', 'BreweryName', 'brewery');

						//Free the results
						if($GetWhoBreweryIsFollowingResults) mysqli_free_result($GetWhoBreweryIsFollowingResults);
					?>
				</div>
				<div class="stdSectionFooter">
					<a onclick="showSRC('FollowingPage.php')" class="moreClicked">more</a>
				</div>
			</div>

			<!-- Beers on tap -->
			<div class="stdSection main" id="highestRated">
				<div class="stdSectionTitle">
					Beers On Tap
				</div>
				<div class="table">
					<?php
						$getFavoritedBeersQuery = "SELECT DISTINCT BeerID, BeerName, PictureURL FROM Beers WHERE OnTap='T' AND  BreweryID = " . $_GET['id'] . " LIMIT 6";
						$favoritedBeersResults = mysqli_query($connection, $getFavoritedBeersQuery);

						createClickableTable($favoritedBeersResults, 'BeerID', 'PictureURL', 'BeerName');

						//Free results
						mysqli_free_result($favoritedBeersResults);

					?>
				</div>
				<div class="stdSectionFooter">
					<a onclick="showSRC('BeerList.html')" class="moreClicked">more</a>
				</div>
			</div>
			<!-- Followers Section -->
			<div class="stdSection FollowingBrewery" id="followersOfBreweries">
				<div class="stdSectionTitle">
					User Followers
				</div>
				<div class="table">
					<!-- User Following Brewery -->
					<?php
						$GetUsersFollowingBrewery = "SELECT u.ProfilePicURL, CONCAT(u.FName, '<br>', u.LName) AS Name, u.Email FROM Users u, UserFollowsBrewery ufb WHERE u.Email = ufb.UserEmail AND ufb.BreweryID=" . $_GET['id'] . " LIMIT 6";
						$GetUsersFollowingBreweryResults = mysqli_query($connection, $GetUsersFollowingBrewery);

						//Create a basic form
						if($GetUsersFollowingBreweryResults) createBasicForm($GetUsersFollowingBreweryResults, 'Email', 'ProfilePicURL', 'Name', 'user');

						//Clear the results
						if($GetUsersFollowingBreweryResults) mysqli_free_result($GetUsersFollowingBreweryResults);
					?>
				</div>
				<div class="stdSectionFooter">
					<a onclick="showSRC('FollowingPage.php')" class="moreClicked">more</a>
				</div>
			</div>

		</div>
	</aside>

	<!-- Main content area -->
	<section class="breweryPage">
		<!-- Display the brewery's cover image -->
		<div>
			<img alt="Brewery Cover Image" id="coverImage" src="<?php echo $CoverPicURL;?>" onclick="showSRC<?php echo "('editCoverPicture.php?id=$id')"; ?>">
		</div>

		<div class="breweryPage newsFeed">
			<!-- For example purposes, add the add brewery panel -->
			<iframe id="contentFrame" src="../html/NewsFeed.html" style="min-width:480px" title="subframe" onload="resizeIframe(this);"></iframe>
			<div class="newsFeed" id="MainArea"></div>

		</div>

	</section>

	<!-- Footer information; additional links etc -->
	<?php
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		//Check which form was set then get the appropriate id.
		if(isset($_POST['brewery'])){
			//Navigate to the brewery page iwth the new id
			echo "<script type=\"text/javascript\"> document.location.href = \"breweryPage.php?id=" . end(array_keys($_POST)) . "\";</script>";
		}
		else if(isset($_POST['followBrew'])){

			//echo "<p style=\"text-align:center; color:red; width:100%; font-size:18px;\">Hit trigger for POST</p>";
			//User is going to Follow the user$
			if($following == 'y'){
				//If the user is currently following the user, unfollow it and change the image
				$DeleteQuery = "DELETE FROM UserFollowsBrewery WHERE UserEmail='" . $signedInUser . "' AND BreweryID='" . $id . "'";
				if(mysqli_query($connection, $DeleteQuery)){
					//Success
					$followText = "Follow";
					$followingImage = "../img/Follow.png?raw=true";
					echo "<script type=\"text/javascript\"> document.location.href = \"breweryPage.php?id=" . $id . "\";</script>";
				}else{
					die("Error: " . mysqli_error($connection));
				}
			}else{
				//If the user is not following the brewery, follow it and change the image.
				//echo $signedInUser . "," . $id . "," . gettype($id);
				$addQuery = "INSERT INTO UserFollowsBrewery (UserEmail, BreweryID) VALUES ('" . $signedInUser ."', '" . $id ."')";
				//echo $addQuery;
				if(mysqli_query($connection, $addQuery)){
					echo "<p style=\"text-align:center; color:red; width:100%; font-size:18px;\">success</p>";
					$followText = "UnFollow";
					$followingImage = "../img/Unfollow_Follow_Color.png?raw=true";
					echo "<script type=\"text/javascript\"> document.location.href = \"breweryPage.php?id=" . $id . "\";</script>";
				} else {
					die("Error: " . mysqli_error($connection));
				}
			}
		}

		//Ends the current session
		session_write_close();

		//Close the sql session
		$connection->close();

		exit();
	}
	?>

</body>

</html>
