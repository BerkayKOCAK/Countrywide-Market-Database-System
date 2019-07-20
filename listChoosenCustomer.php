<?php
	$servername = "localhost";
	$username = "root";
	$password = "mysql";
	$dbname = "berkay_kocak";
	
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}
	
	echo "Sale invoices for customer ".$_POST['customerID']." </br>" ;
	//echo " ".$cname[1];
	$sql = "SELECT DISTRICT.district_name,  CITY.city_name, MARKET.market_name, CONCAT(SALESMAN.salesman_firstname,' ',SALESMAN.salesman_lastname), CONCAT(TRUNCATE(PRODUCT.price*SALE.amount,2),' TL') as price,  SALE.sale_date
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
			JOIN DISTRICT
			ON DISTRICT.district_id = CITY.district_id
			WHERE SALE.customer_id = '".$_POST['customerID']."'

	
			UNION

			SELECT 'TOTAL','','','',CONCAT(TRUNCATE(SUM(PRODUCT.price*SALE.amount),2),' TL'),''
			FROM SALE
			JOIN PRODUCT
			ON PRODUCT.product_id = SALE.product_id
			JOIN CUSTOMER
			ON CUSTOMER.customer_id = SALE.customer_id
			WHERE SALE.customer_id = '".$_POST['customerID']."';";

	
	$result = mysqli_query($conn,$sql) or die("Unsuccessful");
	if (mysqli_num_rows($result) > 0) {
		
		echo "<table border='1'>";
		echo "<tr>
		<td>District Name</td>
		<td>City Name</td>
		<td>Market Name</td>
		<td>Salesman</td>
		<td>Sale Price </td>

		<td>Sale Date</td>
		</tr>";
		while($row = mysqli_fetch_array($result)) {
			echo "<tr>";
		echo "<td>" . $row["district_name"]. "</td>
		<td>" . $row["city_name"]. "</td>
		<td>".$row["market_name"]. "</td>
		<td>".$row["CONCAT(SALESMAN.salesman_firstname,' ',SALESMAN.salesman_lastname)"]."</td>
		<td>".$row["price"]."</td>
		<td>".$row["sale_date"]."</td>";
			echo "</tr>";
		}
		echo "</table>";
	} else {
		echo "0 results";
	}
	mysqli_close($conn);
?>