<?php
session_start();
include("include/config.php");
?>
<!DOCTYPE html>
<html>
<head>
<title>mylokalFood</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="css/w3.css">
<link rel="stylesheet" type="text/css" href="css/mystyle.css">
<!-- Load font and icon library -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
<!-- Header -->
<header>
<?php
include("include/userNav.php");
?>	
</header>	
<!-- Navigation Menu -->
<nav class="topnav">
<?php
include("include/topNav.php");
?>
</nav>
<!-- Page content row -->
<div class="row">
<p style="margin: 15px;"><i class="fa fa-comments-o" style="font-size:24px"></i> / myLokalFood Recommendation </p>
<?php
//include("include/sideNav.php");


if(!empty($_POST["search"])) {
	
	$search_text = $_POST["search"];
	$search_text=ltrim($search_text);
	$search_text=rtrim($search_text);
	
	echo '<p style="margin: 15px;"><b>Related results for "' . $search_text .'"</b></p>';
	$kt=explode(" ",$search_text);
	while(list($key,$val)=each($kt)){
		if($val<>" " and strlen($val) > 0){
		$sql = "SELECT * FROM food WHERE food_name like '%$val%'";
		//echo $sql;
		
		$result = mysqli_query($conn, $sql);	
		if (mysqli_num_rows($result) > 0) {	
			//echo '<p style="margin: 15px;"><b>Related results for ' .$val .'</b></p>';
			while($row = mysqli_fetch_assoc($result)) {
				echo "&nbsp;&nbsp;&nbsp;&nbsp;" . $row["food_name"] . "<br>";
			}
		}//end if result
			else {
				echo "<p>Sorry, no result for $val</p>";
			}
		}// end of while list string
	}
	
}
//start recommendation
//1) most frequent order 2) high star 3) high positive review

	$sqlFreq = "SELECT food.food_id, food.food_name, count(*) as TotOder FROM food_review, food WHERE food_review.food_id = food.food_id GROUP BY food_review.food_id ORDER BY TotOder DESC LIMIT 5";
	
	$result2 = mysqli_query($conn, $sqlFreq);	
		if (mysqli_num_rows($result2) > 0) {	
			echo '<p style="margin: 15px;"><b>Most Popular Order</b> <i class="fa fa-fire" style="font-size:24px;color:red"></i></p>';
			while($rowFreq = mysqli_fetch_assoc($result2)) {				
				echo "&nbsp;&nbsp;&nbsp;&nbsp;" . $rowFreq["food_name"] . ", " . $rowFreq["TotOder"] . "<br>";				
				//echo '<p style="margin: 15px;">' .$rowFreq["food_name"] . ' ' . $rowFreq["TotOder"] . '</p>';
			}
		}//end if result
			

	$sqlRate = "SELECT food.food_id, food.food_name, AVG(food_review.food_ratings) as AVGRATE FROM food_review, food WHERE food_review.food_id = food.food_id GROUP BY food_review.food_id ORDER BY AVGRATE DESC LIMIT 5";
	
	$result3 = mysqli_query($conn, $sqlRate);	
		if (mysqli_num_rows($result3) > 0) {	
			echo '<p style="margin: 15px;"><b>Highly Rated</b> <i class="fa fa-star checked"></i></p>';
			while($rowRate = mysqli_fetch_assoc($result3)) {
				echo "&nbsp;&nbsp;&nbsp;&nbsp;" . $rowRate["food_name"] . ", " . round($rowRate["AVGRATE"],1) . " of 5<br>";
			}
		}//end if result
	
	$sqlPos = "SELECT food.food_id, food.food_name, count(*) as TotPositive FROM food_review, food WHERE food_review.food_id = food.food_id AND food_review.food_sentiment = 'Positive' GROUP BY food_review.food_id ORDER BY TotPositive DESC LIMIT 5";	
	
	$result4 = mysqli_query($conn, $sqlPos);	
		if (mysqli_num_rows($result4) > 0) {	
			echo '<p style="margin: 15px;"><b>Most Positive Review</b> <i class="fa fa-thumbs-o-up"></i></p>';
			while($rowPos = mysqli_fetch_assoc($result4)) {
				echo "&nbsp;&nbsp;&nbsp;&nbsp;" . $rowPos["food_name"] . ", " . $rowPos["TotPositive"] . " customer enjoyed this food<br>";
			}
		}//end if result

?>
</div>
</body>
</html>
