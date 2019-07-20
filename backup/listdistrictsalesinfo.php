<?php
	$servername = "localhost";
	$username = "root";
	$password = "mysql";
	$dbname = "berkay_kocak";
	
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}
	
	$sql = "SELECT m1.Market,m1.City,m1.District,CONCAT(m1.total_earning,' TL') AS 'Total Market Income',m2.salesman AS 'Biggest Sale Salesman',m2.counter AS 'Biggest Sale',m2.total AS 'Biggest Salesman Sale Amount',m3.salesman AS 'Least Sale Salesman',m3.counter AS 'Least Sale',m3.total AS 'Least Sale Amount'
FROM(
		 SELECT * FROM( SELECT DISTRICT.district_name AS District, CITY.city_name AS City, MARKET.market_id AS ID, MARKET.market_name AS Market, SUM(PRODUCT.price*SALE.amount) AS total_earning
						FROM SALE
						JOIN PRODUCT
						ON PRODUCT.product_id = SALE.product_id
						JOIN SALESMAN
						ON SALESMAN.salesman_id = SALE.salesman_id
						JOIN MARKET
						ON MARKET.market_id = SALESMAN.market_id
						JOIN CITY
						ON CITY.city_id = MARKET.city_id
						JOIN DISTRICT
						ON DISTRICT.district_id = CITY.district_id
						WHERE DISTRICT.district_name = '".$_POST["districtname"]."'
						GROUP BY MARKET.market_id
						ORDER BY MARKET.market_id) AS s1) AS m1

JOIN (
		SELECT * FROM( SELECT DISTRICT.district_name AS District, CITY.city_name AS City, MARKET.market_id AS ID, MARKET.market_name AS Market, CONCAT(SALESMAN.salesman_firstname,' ',SALESMAN.salesman_lastname) AS salesman, COUNT(SALESMAN.salesman_id) AS counter, SUM(SALE.amount) AS total
						FROM SALE
						JOIN SALESMAN
						ON SALESMAN.salesman_id = SALE.salesman_id
						JOIN MARKET
						ON MARKET.market_id = SALESMAN.market_id
						JOIN CITY
						ON CITY.city_id = MARKET.city_id
						JOIN DISTRICT
						ON DISTRICT.district_id = CITY.district_id
						WHERE DISTRICT.district_name = '".$_POST["districtname"]."'
						GROUP BY SALESMAN.salesman_id
						ORDER BY counter DESC, total DESC) AS s2
 GROUP BY s2.ID
 ORDER BY s2.ID ASC) AS m2
 ON m1.ID = m2.ID
 JOIN (
		SELECT * FROM( SELECT DISTRICT.district_name AS District, CITY.city_name AS City, MARKET.market_id AS ID, MARKET.market_name AS Market, CONCAT(SALESMAN.salesman_firstname,' ',SALESMAN.salesman_lastname) AS salesman, COUNT(SALESMAN.salesman_id) AS counter, SUM(SALE.amount) AS total
						FROM SALE
						JOIN SALESMAN
						ON SALESMAN.salesman_id = SALE.salesman_id
						JOIN MARKET
						ON MARKET.market_id = SALESMAN.market_id
						JOIN CITY
						ON CITY.city_id = MARKET.city_id
						JOIN DISTRICT
						ON DISTRICT.district_id = CITY.district_id
						WHERE DISTRICT.district_name = '".$_POST["districtname"]."'
						GROUP BY SALESMAN.salesman_id
						ORDER BY counter ASC, total ASC) AS s3
 GROUP BY s3.ID
 ORDER BY s3.ID ASC) AS m3
 ON m1.ID = m3.ID;";
 
	$result = mysqli_query($conn,$sql) or die("Unsuccessful");
	
	if (mysqli_num_rows($result) > 0) {
		echo "Market sales information for ".$_POST["districtname"]."</br>";
		echo "<table border='1'>";
		echo "<tr><td>Market</td><td>City</td><td>District</td><td>Total Market Income</td><td>Biggest Sale Salesman</td><td>Biggest Sale Number</td><td>Biggest Salesman Sale Amount</td><td>Least Sale Salesman</td><td>Least Sale Number</td><td>Least Sale Amount</td></tr>";
		while($row = mysqli_fetch_array($result)) {
			echo "<tr>";
		echo "<td>" . $row["Market"]. "</td><td>" . $row["City"]. "</td><td>".$row["District"]. "</td><td>".$row["Total Market Income"]."</td><td>".$row["Biggest Sale Salesman"]."</td><td>".$row["Biggest Sale"]."</td><td>".$row["Biggest Salesman Sale Amount"]."</td><td>".$row["Least Sale Salesman"]."</td><td>".$row["Least Sale"]."</td><td>".$row["Least Sale Amount"]."</td>";
			echo "</tr>";
		}
		echo "</table>";
	} else {
		echo "0 results";
	}
	echo "</br></br>";
	
	$sql = "SELECT m1.District,m1.City,m1.Market,m1.Salesman,CONCAT(m1.income,' TL') AS 'Income' ,CONCAT(m2.sale,' TL') AS 'Maximum Sale',m2.Customer AS 'Maximum Sale Customer',m2.Date AS 'Maximum Sale Date',CONCAT(m3.sale,' TL') AS 'Minimum Sale',m3.Customer AS 'Minimum Sale Customer',m3.Date AS 'Minimum Sale Date'
			FROM(
			SELECT * FROM(SELECT DISTRICT.district_name AS District, CITY.city_name AS City, MARKET.market_name AS Market, CONCAT(SALESMAN.salesman_firstname,' ',SALESMAN.salesman_lastname) AS Salesman, SUM(PRODUCT.price*SALE.amount) AS income
			FROM SALE
			JOIN PRODUCT
			ON PRODUCT.product_id = SALE.product_id
			JOIN SALESMAN
			ON SALESMAN.salesman_id = SALE.salesman_id
			JOIN MARKET
			ON MARKET.market_id = SALESMAN.market_id
			JOIN CITY
			ON CITY.city_id = MARKET.city_id
			JOIN DISTRICT
			ON DISTRICT.district_id = CITY.district_id
			WHERE DISTRICT.district_name = '".$_POST["districtname"]."'
			GROUP BY SALESMAN.salesman_id
			ORDER BY Salesman) AS s1) AS m1
			 
			 
			 
			JOIN(
			 
			SELECT * FROM (SELECT CONCAT(SALESMAN.salesman_firstname,' ',SALESMAN.salesman_lastname) AS Salesman, PRODUCT.price*SALE.amount AS sale, CONCAT(CUSTOMER.customer_firstname,' ',CUSTOMER.customer_lastname) AS Customer, SALE.sale_date AS Date
			FROM SALE
			JOIN CUSTOMER
			ON CUSTOMER.customer_id = SALE.customer_id
			JOIN PRODUCT
			ON PRODUCT.product_id = SALE.product_id
			JOIN SALESMAN
			ON SALESMAN.salesman_id = SALE.salesman_id
			JOIN MARKET
			ON MARKET.market_id = SALESMAN.market_id
			JOIN CITY
			ON CITY.city_id = MARKET.city_id
			JOIN DISTRICT
			ON DISTRICT.district_id = CITY.district_id
			WHERE DISTRICT.district_name = '".$_POST["districtname"]."'
			ORDER BY sale DESC) AS s2
			GROUP BY s2.Salesman
			ORDER BY s2.Salesman) AS m2
			ON m1.Salesman = m2.Salesman
			 
			JOIN(
			 
			SELECT * FROM (SELECT CONCAT(SALESMAN.salesman_firstname,' ',SALESMAN.salesman_lastname) AS Salesman, PRODUCT.price*SALE.amount AS sale, CONCAT(CUSTOMER.customer_firstname,' ',CUSTOMER.customer_lastname) AS Customer, SALE.sale_date AS Date
			FROM SALE
			JOIN CUSTOMER
			ON CUSTOMER.customer_id = SALE.customer_id
			JOIN PRODUCT
			ON PRODUCT.product_id = SALE.product_id
			JOIN SALESMAN
			ON SALESMAN.salesman_id = SALE.salesman_id
			JOIN MARKET
			ON MARKET.market_id = SALESMAN.market_id
			JOIN CITY
			ON CITY.city_id = MARKET.city_id
			JOIN DISTRICT
			ON DISTRICT.district_id = CITY.district_id
			WHERE DISTRICT.district_name = '".$_POST["districtname"]."'
			ORDER BY sale ASC) AS s3
			GROUP BY s3.Salesman
			ORDER BY s3.Salesman) AS m3

			ON m1.Salesman = m3.Salesman;";
	
	$result = mysqli_query($conn,$sql) or die("Unsuccessful");
	
	if (mysqli_num_rows($result) > 0) {
		echo "Salesman sales information for ".$_POST["districtname"]."</br>";
		echo "<table border='1'>";
		echo "<tr><td>District</td><td>City</td><td>Market</td><td>Salesman</td><td>Income</td><td>Maximum Sale</td><td>Maximum Sale Customer</td><td>Maximum Sale Date</td><td>Minimum Sale</td><td>Minimum Sale Custome</td><td>Minimum Sale Date</td></tr>";
		while($row = mysqli_fetch_array($result)) {
			echo "<tr>";
		echo "<td>" . $row["District"]. "</td><td>" . $row["City"]. "</td><td>".$row["Market"]. "</td><td>".$row["Salesman"]."</td><td>".$row["Income"]."</td><td>".$row["Maximum Sale"]."</td><td>".$row["Maximum Sale Customer"]."</td><td>".$row["Maximum Sale Date"]."</td><td>".$row["Minimum Sale"]."</td><td>".$row["Minimum Sale Customer"]."</td><td>".$row["Minimum Sale Date"]."</td>";
			echo "</tr>";
		}
		echo "</table>";
	} else {
		echo "0 results";
	}
	
	
	
	mysqli_close($conn);
?>