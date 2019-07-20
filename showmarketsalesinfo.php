

<?php
	$servername = "localhost";
	$username = "root";
	$password = "mysql";
	$dbname = "berkay_kocak";
	
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}
	$props = explode(" ",$_POST['marketname']);
	echo "1  ".$props[1];
	echo "4  ".$props[4];

	$sql = "SELECT CONCAT(CUSTOMER.customer_firstname,' ',CUSTOMER.customer_lastname), SUM(SALE.amount), CONCAT(SUM(PRODUCT.price*SALE.amount),' TL'), SALE.salesman_id ,PRODUCT.product_id
	FROM SALE
	JOIN CUSTOMER
	ON CUSTOMER.customer_id = SALE.customer_id
	JOIN SALESMAN
	ON SALESMAN.salesman_id = SALE.salesman_id
	JOIN PRODUCT
	ON PRODUCT.product_id = SALE.product_id
	JOIN MARKET
	ON MARKET.market_id = SALESMAN.market_id
	JOIN CITY
	ON CITY.city_id = MARKET.city_id
	WHERE CITY.city_name = '".$props[1]."'
	AND SALESMAN.market_id = '".$props[4]."'
	GROUP BY CUSTOMER.customer_id;";

	$result = mysqli_query($conn,$sql);
	
	if (mysqli_num_rows($result) > 0) {
		echo "Customer sales info for market with id " .$props[4]."</br>";
		echo "<table border='1'>";
		echo "<tr><td>Customer</td>
		<td>Total Amount</td>
		<td>Total Price</td>
		<td>Product ID</td>
		<td>Salesman ID</td>
		</tr>";
		while($row = mysqli_fetch_array($result)) {
			echo "<tr>";
		echo "<td>" . $row["CONCAT(CUSTOMER.customer_firstname,' ',CUSTOMER.customer_lastname)"]. "</td>
		<td>" . $row["SUM(SALE.amount)"]. "</td>
		<td>".$row["CONCAT(SUM(PRODUCT.price*SALE.amount),' TL')"]. "</td>
		<td>" . $row["product_id"]. "</td>
		<td>" . $row["salesman_id"]. "</td>";
			echo "</tr>";
		}
		echo "</table>";
	} else {
		echo "0 results";
	}
	
	echo "</br></br>";


	$sql = "SELECT PRODUCT.product_name, SUM(SALE.amount), CONCAT(SUM(PRODUCT.price*SALE.amount),' TL') ,PRODUCT.product_id
			FROM SALE

			JOIN CUSTOMER
			ON CUSTOMER.customer_id = SALE.customer_id
			JOIN SALESMAN
			ON SALESMAN.salesman_id = SALE.salesman_id
			JOIN PRODUCT
			ON PRODUCT.product_id = SALE.product_id
			JOIN MARKET
			ON MARKET.market_id = SALESMAN.market_id
			JOIN CITY
			ON CITY.city_id = MARKET.city_id
			WHERE MARKET.market_id =  '".$props[4]."'
			AND CITY.city_name = '".$props[1]."'
			GROUP BY PRODUCT.product_id;";
	$result = mysqli_query($conn,$sql);
	
	if (mysqli_num_rows($result) > 0) {
		echo "Product sales info for market with id ".$props[4]."</br>";
		echo "<table border='1'>";
		echo "<tr><td>Product</td><td>Product ID</td><td>Total Amount</td><td>Total Price</td></tr>";
		while($row = mysqli_fetch_array($result)) {
			echo "<tr>";
		echo "<td>" . $row["product_name"]. "</td>
		<td>" . $row["product_id"]. "</td>
		<td>" . $row["SUM(SALE.amount)"]. "</td>
		<td>".$row["CONCAT(SUM(PRODUCT.price*SALE.amount),' TL')"]. "</td>";
			echo "</tr>";
		}
		echo "</table>";
	} else {
		echo "0 results";
	}
	
	echo "</br></br>";
	
	$sql = "SELECT CONCAT(SALESMAN.salesman_firstname,' ',SALESMAN.salesman_lastname), SUM(SALE.amount), CONCAT(SUM(PRODUCT.price*SALE.amount),' TL'), SALE.salesman_id
			FROM SALE
			JOIN SALESMAN
			ON SALESMAN.salesman_id = SALE.salesman_id
			JOIN PRODUCT
			ON PRODUCT.product_id = SALE.product_id
			JOIN MARKET
			ON MARKET.market_id = SALESMAN.market_id
			JOIN CITY
			ON CITY.city_id = MARKET.city_id
			WHERE MARKET.market_id =  '".$props[4]."'
			AND CITY.city_name = '".$props[1]."'
			GROUP BY SALESMAN.salesman_id;";
	
	$result = mysqli_query($conn,$sql);
	
	if (mysqli_num_rows($result) > 0) {
		echo "Salesman sales info for market with id ".$props[4]."</br>";
		echo "<table border='1'>";
		echo "<tr><td>Salesman</td><td>Total Amount</td><td>Total Price</td><td>Salesman ID</td></tr>";
		while($row = mysqli_fetch_array($result)) {
			echo "<tr>";
		echo "<td>" . $row["CONCAT(SALESMAN.salesman_firstname,' ',SALESMAN.salesman_lastname)"]. "</td>
		<td>" . $row["SUM(SALE.amount)"]. "</td>
		<td>".$row["CONCAT(SUM(PRODUCT.price*SALE.amount),' TL')"]. "</td>
		<td>" . $row["salesman_id"]. "</td>";
			echo "</tr>";
		}
		echo "</table>";
	} else {
		echo "0 results";
	}



	
?>	
<br/>
<form action="listChoosenSalesman.php" method="post">
<?php



$sql = "SELECT CONCAT(SALESMAN.salesman_firstname,' ',SALESMAN.salesman_lastname) as salesman, SUM(SALE.amount), CONCAT(SUM(PRODUCT.price*SALE.amount),' TL'), SALE.salesman_id
			FROM SALE
			JOIN SALESMAN
			ON SALESMAN.salesman_id = SALE.salesman_id
			JOIN PRODUCT
			ON PRODUCT.product_id = SALE.product_id
			JOIN MARKET
			ON MARKET.market_id = SALESMAN.market_id
			JOIN CITY
			ON CITY.city_id = MARKET.city_id
			WHERE MARKET.market_id =  '".$props[4]."'
			AND CITY.city_name = '".$props[1]."'
			GROUP BY SALESMAN.salesman_id;";
 	$result = mysqli_query($conn,$sql);

 if (mysqli_num_rows($result) > 0) {
	 echo "Select a Salesman: </br>";
	 echo "<select name='salesmanID'>";
	 while($row = mysqli_fetch_array($result)) {
		 echo "<option value='" . $row["salesman_id"]. "'>  ". $row["salesman"]. "  </option>";
	 }
	 echo "</select>";
	} else {
		echo "0 results";
	}
	

?>
<input type="submit" value="Get Salesman">
</form>

<br/>

<form action="listChoosenCustomer.php" method="post">
<?php


$sql = "SELECT CONCAT(CUSTOMER.customer_firstname,' ',CUSTOMER.customer_lastname) as customer, SALE.customer_id as customer_ID
			FROM SALE
			JOIN CUSTOMER
			ON CUSTOMER.customer_id = SALE.customer_id
			JOIN SALESMAN
			ON SALESMAN.salesman_id = SALE.salesman_id
			JOIN PRODUCT
			ON PRODUCT.product_id = SALE.product_id
			JOIN MARKET
			ON MARKET.market_id = SALESMAN.market_id
			JOIN CITY
			ON CITY.city_id = MARKET.city_id
			WHERE MARKET.market_id =  '".$props[4]."'
			AND CITY.city_name = '".$props[1]."';";

 	$result = mysqli_query($conn,$sql);

 if (mysqli_num_rows($result) > 0) {
	 echo "Select a customer: </br>";
	 echo "<select name='customerID'>";
	 while($row = mysqli_fetch_array($result)) {
		 echo "<option value='" . $row["customer_ID"]. "'>  ". $row["customer"]. "</option>";
	 }
	 echo "</select>";
	} else {
		echo "0 results";
	}
	
	mysqli_close($conn);
?>
<input type="submit" value="Get Customer">
</form