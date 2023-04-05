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
<style>
{
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-size: 14px;
}
body {
    -webkit-font-smoothing: antialiased;
    -webkit-text-size-adjust: none;
    width: 100% !important;
    height: 100%;
    line-height: 1.6;
}
table td {
    vertical-align: top;
}

/* -------------------------------------
    BODY & CONTAINER
------------------------------------- */
body {
    background-color: #f6f6f6;
}

.body-wrap {
    background-color: #f6f6f6;
    width: 100%;
}

.container {
    display: block !important;
    max-width: 600px !important;
    margin: 0 auto !important;
    /* makes it centered */
    clear: both !important;
}

.content {
    max-width: 600px;
    margin: 0 auto;
    display: block;
    padding: 20px;
}

/* -------------------------------------
    HEADER, FOOTER, MAIN
------------------------------------- */
.main {
    background: #fff;
    border: 1px solid #e9e9e9;
    border-radius: 3px;
}

.content-wrap {
    padding: 20px;
}

.content-block {
    padding: 0 0 20px;
}

.header {
    width: 100%;
    margin-bottom: 20px;
}

.footer {
    width: 100%;
    clear: both;
    color: #999;
    padding: 20px;
}
.footer a {
    color: #999;
}
.footer p, .footer a, .footer unsubscribe, .footer td {
    font-size: 12px;
}

/* -------------------------------------
    TYPOGRAPHY
------------------------------------- */
h1, h2, h3 {
    font-family: "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif;
    color: #000;
    margin: 40px 0 0;
    line-height: 1.2;
    font-weight: 400;
}
.btn-primary {
    text-decoration: none;
    color: #FFF;
    background-color: #1ab394;
    border: solid #1ab394;
    border-width: 5px 10px;
    line-height: 2;
    font-weight: bold;
    text-align: center;
    cursor: pointer;
    display: inline-block;
    border-radius: 5px;
}
</style>
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
<p style="margin: 15px;"><i class="fa fa-shopping-cart" style="font-size:24px"></i> Thank you!We are preparing your order.</td><br>

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
?>
<div class="col-mid">
<?php  
	$sql = "SELECT order_id, order_datetime, order_amt from order_invoice where order_id = $order_id";
	$retval = mysqli_query($conn,$sql);
	$row = mysqli_fetch_assoc($retval);
?>
<form action="my_order.php" onsubmit="return validation" method="POST">
<table style="width: 50%">
<tbody>
<tr>
	<th colspan="3" style="padding-top: 12px; padding-bottom: 12px; text-align: center; background-color: #909090; color: white;" width="15%">Customer Receipt</th>
</tr>
<tr>
	<td align="left"><?php echo "Customer Name: ".$_SESSION["userName"]; ?></td>
	<td align="center"><?php echo "Customer ID: ".$_SESSION["UID"]; ?></td>
	<td align="center"><?php echo "Order ID: ".$row["order_id"]; ?></td>
</tr>
<?php
	$total_quantity = 0;  
	$sql2 = "SELECT food.food_name, food.food_price, order_line.food_qty from food, order_line where food.food_id = order_line.food_id and order_line.order_id = '$order_id'";
	$retval = mysqli_query($conn,$sql2);
   	while($row2 = mysqli_fetch_assoc($retval)){
	?>
	<tr>
	<td align="left"><?php echo $row2["food_name"]; ?></td>
	<td align="center"><?php echo "Price RM ".$row2["food_price"]; ?></td>
	<td align="center"><?php echo "Food Quantity: ".$row2["food_qty"]; ?></td>
	<?php $total_quantity += $row2["food_qty"];?> 
	</tr>
	<?php 
	}?>
	<td align="left"><?php echo "Date Generate: " .$row["order_datetime"]; ?></td>
	<td align="center"><?php echo "Total Amount RM: " .$row["order_amt"];?></td>
	<td align="center"><?php echo "Item quantity: " .$total_quantity; ?></td>
</table>
<br>
</tbody><button onclick="location.href='index.php'" type="button">Home</button>

</div>

<?php  
	mysqli_close($conn);
	unset($_SESSION["cart_item"]);//unset cart 
?>

<!--<p style="margin: 15px;"><a href="index.php">Continue Shopping</a></p>-->
</body>
</html>