<?php
session_start();
include("include/config.php");

//check login
if(!isset($_SESSION["UID"])){
	header("location:login.php");	
}
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
<p style="margin: 15px;"><i class="fa fa-shopping-cart" style="font-size:24px"></i> / Checkout Information</p>

<?php
if(!empty($_GET["price"])) {
	$order_amt = $_GET["price"];
	$order_status = 1;//1= New, 2=Process, 3= Completed
	
	//sql for order_invoice table
	$sql = "INSERT INTO order_invoice (order_status, order_amt, cust_id)
		VALUES ('" . $order_status . "','" . $order_amt . "','" . $_SESSION["UID"] . "')";
	
		if (mysqli_query($conn, $sql)) {	
			echo "&nbsp;&nbsp;&nbsp;&nbsp; New customer order record has the Order id: " . mysqli_insert_id($conn) . "<br>";	
			$order_id = mysqli_insert_id($conn);
		} else {
		echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}	
		
	foreach ($_SESSION["cart_item"] as $item){	
		//sql for order_line table
		$sql2 = "INSERT INTO order_line (order_id, food_id, food_qty)
		VALUES ('" . $order_id . "','" . $item["prodID"] . "','" . $item["quantity"] . "')";
	
	if (mysqli_query($conn, $sql2)) {
			//echo "<p>New customer order record created successfully.";	
			echo "&nbsp;&nbsp;&nbsp;&nbsp; Order line record has the Line id: " . mysqli_insert_id($conn) . "<br>";	
			$line_id = mysqli_insert_id($conn);
		} else {
		echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}
	}

}
mysqli_close($conn);
unset($_SESSION["cart_item"]);//unset cart
?>
<!--<p style="margin: 15px;"><a href="index.php">Continue Shopping</a></p>-->
</body>
</html>