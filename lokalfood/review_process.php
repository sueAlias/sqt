<?php	
session_start();
include("include/config.php");

	$order_id = $_POST["order_id"];
	$food_id = $_POST["food_id"];
	$food_review = escapeshellarg($_POST["review"]);
	$food_ratings = $_POST["rate"];
	
	//echo "Displaying Output: <br>";
	$output = exec("C:\Users\Suraya\AppData\Local\Programs\Python\Python38\python.exe review_process.py $food_review"); 
		
	//echo "Sentiment Polarity = " . $output;
	//echo $order_id . "- " . $food_id;
	//print_r (explode(" ",$output));
	$sentiment = explode(" ",$output);
	
	$polarity = $sentiment[0];
	$subjectivity = $sentiment[1];
	$polarity = round($polarity,1);
	$subjectivity = round($subjectivity,1);
	
	//echo $polarity . " | " . $subjectivity . " | " . $food_ratings;
	
	if ($polarity < 0 ){
		//echo "Negative";
		$result = "Negative";
	}
	else if ($polarity == 0 ){
		//echo "Neutral";
		$result = "Neutral";
	}
	else {
		//echo "Positive";
		$result = "Positive";
	}
    
	//echo "Polarity: " . $polarity . " Subjectivity: " . $subjectivity . " Rating: " . $food_ratings . " //Sentiment Result: " . $result;
	
	//Insert to table food_review
	$sql = "INSERT INTO food_review(cust_id, order_id, food_id, food_review,food_ratings, food_polarity, food_subjv, food_sentiment) VALUES ('" . $_SESSION["UID"] . "','" . $order_id . "','" . $food_id . "','" . addslashes($food_review) . "','" . $food_ratings ."','" . $polarity ."','" . $subjectivity ."','" . $result ."')";
	
	if (mysqli_query($conn, $sql)) {	
			//echo "<br>New customer review record has the Review id: " . mysqli_insert_id($conn);
		} else {
		echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}
	
	/* $arrlength = count($polarity);
	echo "array length : " . $arrlength;
	for($x = 0; $x < $arrlength; $x++) {
		echo $polarity[$x];
	echo "<br>";
	} */
	
/* 	
//Sentiment Analysis
The sentiment property returns a named tuple of the form Sentiment(polarity, subjectivity). The polarity score is a float within the range [-1.0, 1.0]. The subjectivity is a float within the range [0.0, 1.0] where 0.0 is very objective and 1.0 is very subjective. 
 Subjective sentences generally refer to personal opinion, emotion or judgment whereas objective refers to factual information
 https://textblob.readthedocs.io/en/dev/classifiers.html
 https://stackabuse.com/sentiment-analysis-in-python-with-textblob
 */
?>
<!DOCTYPE html>
<html>
<head>
<title>mylokalFood</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="css/w3.css">
<link rel="stylesheet" type="text/css" href="css/mystyle.css">
<link rel="stylesheet" type="text/css" href="css/star.css">
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

<p style="margin: 15px;"><i class="fa fa-cogs" style="font-size:24px"></i> / Sentiment Analysis</p>

<p style="margin: 15px;">
<?php echo $food_review . "<br>"; ?>
<?php echo "<b>Polarity</b>: " . $polarity . ", <b>Subjectivity</b>: " . $subjectivity . ", <b>Rating</b>: " . $food_ratings . ", <b>Sentiment Result</b>: " . $result; ?></p>
<!--<p style="margin: 15px;"><a href="index.php">Continue Shopping</a></p>-->
</div>
</body>
</html>