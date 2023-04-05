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
<div class="row">
<p style="margin: 15px;"><i class="fa fa-comments-o" style="font-size:24px"></i> / Food Review Sentiment Analysis </p>
<?php 

	$food_id = $_GET["food-id"];
	$food_name = $_GET["food-name"];
	echo '<p style="margin: 15px;"><b>' . $food_name . '</b></p>';
	
	$sql = "SELECT food_sentiment, count(*) AS SentiCount FROM food_review WHERE food_id = $food_id GROUP BY food_sentiment ORDER BY food_sentiment DESC";
	
	$result = mysqli_query($conn, $sql);

	if (mysqli_num_rows($result) > 0) {
	// output data of each row
		while($row = mysqli_fetch_assoc($result)) {
?>
		
		<?php 
		$sentiment = $row["food_sentiment"];
		if ($sentiment == "Positive"){
			$label = "fa fa-thumbs-o-up";
		}
		else if ($sentiment == "Neutral"){
			$label = "fa fa-check-square-o";
		}
		else {
			$label = "fa fa-thumbs-o-down";
		}
		?>
		
		<p style="margin: 15px;"><i class="<?php echo $label ?>"></i>
		<?php echo "<b>" . $row["food_sentiment"]. " : " . $row["SentiCount"]. " " . "</b><br>";?>
		</p>
			
		<?php
			$sql2 = "SELECT food_ratings, review_id, food_sentiment, food_review FROM food_review WHERE food_id = $food_id AND food_sentiment = '" . $sentiment . "' ORDER BY review_id DESC";
			
			//echo $sql2;
			$result2 = mysqli_query($conn, $sql2);
			if (mysqli_num_rows($result2) > 0) {
				while($row2 = mysqli_fetch_assoc($result2)) {	
				echo '<p style="margin: 15px;"> <span class="fa fa-star checked"></span>' . $row2["food_ratings"] . ' </p>';
				//echo '<p style="margin: 15px;">' . $row2["food_review"] . '</p>';
				echo "&nbsp;&nbsp;&nbsp;&nbsp;" . $row2["food_review"] . "<br>";
				//"<p>" . $City . " is in " . $Country . ".</p>";

				}
			}
		}
	}

?>

</div>
</body>
</html>
