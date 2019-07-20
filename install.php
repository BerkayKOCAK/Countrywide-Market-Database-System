<?php
$servername = "localhost";
$username = "root";
$password = "mysql";


/// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "DROP DATABASE IF EXISTS berkay_kocak;";
	mysqli_query($conn,$sql);
	
	
	$sql = "CREATE DATABASE berkay_kocak;";
	mysqli_query($conn,$sql);
	
	mysqli_select_db($conn,"berkay_kocak");
	
// sql to create table
$sql = "CREATE TABLE DISTRICT (
    district_id INT(11) NOT NULL AUTO_INCREMENT,
    district_name VARCHAR(50) NOT NULL,
    PRIMARY KEY(district_id)
    )ENGINE=InnoDB;";
    mysqli_query($conn,$sql);

$sql = "CREATE TABLE CITY (
    city_id INT(11) NOT NULL AUTO_INCREMENT,
    city_name VARCHAR(50) NOT NULL,
    district_id INT(11) NOT NULL,
    PRIMARY KEY(city_id),
    FOREIGN KEY (district_id) REFERENCES DISTRICT(district_id)
    )ENGINE=InnoDB;";
    mysqli_query($conn,$sql);

$sql = "CREATE TABLE MARKET (
    market_id INT(11) NOT NULL AUTO_INCREMENT,
    market_name VARCHAR(50) NOT NULL,
    city_id INT(11) NOT NULL,
    PRIMARY KEY(market_id),
    FOREIGN KEY (city_id) REFERENCES CITY(city_id)
    )ENGINE=InnoDB;";
	mysqli_query($conn,$sql);
    
$sql = "CREATE TABLE SALESMAN (
        salesman_id INT(11) NOT NULL AUTO_INCREMENT,
        salesman_firstname VARCHAR(50) NOT NULL,
        salesman_lastname VARCHAR(50) NOT NULL,
        market_id INT(11) NOT NULL,
        PRIMARY KEY(salesman_id),
        FOREIGN KEY (market_id) REFERENCES MARKET(market_id)
        )ENGINE=InnoDB;";
        mysqli_query($conn,$sql);

$sql = "CREATE TABLE CUSTOMER (
    customer_id INT(11) NOT NULL AUTO_INCREMENT,
    customer_firstname VARCHAR(50) NOT NULL,
    customer_lastname VARCHAR(50) NOT NULL,
    PRIMARY KEY(customer_id)
    )ENGINE=InnoDB;";
    mysqli_query($conn,$sql);

$sql = "CREATE TABLE PRODUCT (
    product_id INT(11) NOT NULL AUTO_INCREMENT,
    product_code VARCHAR(50) NOT NULL,
    product_name VARCHAR(50) NOT NULL,
    price DOUBLE NOT NULL,
    PRIMARY KEY(product_id)
    )ENGINE=InnoDB;";
    mysqli_query($conn,$sql);

$sql = "CREATE TABLE SALE (
    sale_id INT(11) NOT NULL AUTO_INCREMENT,
    product_id INT(11) NOT NULL,
    salesman_id INT(11) NOT NULL,
    customer_id INT(11) NOT NULL,
    sale_date DATE NOT NULL,
    amount INT(11) NOT NULL,
    PRIMARY KEY(sale_id),
    FOREIGN KEY (product_id) REFERENCES PRODUCT(product_id),
    FOREIGN KEY (salesman_id) REFERENCES SALESMAN(salesman_id),
    FOREIGN KEY (customer_id) REFERENCES CUSTOMER(customer_id)
    )ENGINE=InnoDB;";
	mysqli_query($conn,$sql);

$filename = "csv/district.csv";
	
	$sql = "LOAD DATA LOCAL INFILE '".$filename."' 
			INTO TABLE DISTRICT
			FIELDS TERMINATED BY ';'
			LINES TERMINATED BY '\r\n'
            (district_name);";
    mysqli_query($conn,$sql);

$filename = "csv/city.csv";
	
	$sql = "LOAD DATA LOCAL INFILE '".$filename."' 
			INTO TABLE CITY
			FIELDS TERMINATED BY ';'
			LINES TERMINATED BY '\r\n'
            (city_name,district_id);";
    mysqli_query($conn,$sql);

$filename = "csv/market.csv";

$sql = "LOAD DATA LOCAL INFILE '".$filename."' 
			INTO TABLE MARKET
            FIELDS TERMINATED BY ';'
			LINES TERMINATED BY '\r\n'
            (market_name,city_id);";
	
    mysqli_query($conn,$sql);
    
$filename = "csv/salesman.csv";
	
	$sql = "LOAD DATA LOCAL INFILE '".$filename."' 
			INTO TABLE SALESMAN
            FIELDS TERMINATED BY ';'
			LINES TERMINATED BY '\r\n'
            (salesman_firstname,salesman_lastname,market_id);";
	
    mysqli_query($conn,$sql);
    
$filename = "csv/customer.csv";
	
	$sql = "LOAD DATA LOCAL INFILE '".$filename."' 
			INTO TABLE CUSTOMER
            FIELDS TERMINATED BY ';'
			LINES TERMINATED BY '\r\n'
            (customer_firstname,customer_lastname);";
	
    mysqli_query($conn,$sql);
    
$filename = "csv/product.csv";
	
	$sql = "LOAD DATA LOCAL INFILE '".$filename."' 
			INTO TABLE PRODUCT
            FIELDS TERMINATED BY ';'
			LINES TERMINATED BY '\r\n'
            (product_code,product_name,price);";
	
    mysqli_query($conn,$sql);

$filename = "csv/sale.csv";
	
	$sql = "LOAD DATA LOCAL INFILE '".$filename."' 
			INTO TABLE SALE
            FIELDS TERMINATED BY ';'
			LINES TERMINATED BY '\r\n'
            (product_id,salesman_id,customer_id,sale_date,amount);";
	
    mysqli_query($conn,$sql);


echo "Database succesfully installed.";    
?>

<html>
<body>
<br/>
<form action="drawcitysalesinfo.php" method="post">
	<?php

		//$conn = mysqli_connect($servername, $username, $password, $dbname);

		if (!$conn) {
			die("Connection failed: " . mysqli_connect_error());
		} 

		$sql = "SELECT city_name FROM CITY";
		$result = mysqli_query($conn,$sql);

		if (mysqli_num_rows($result) > 0) {
			echo "Select a city: </br>";
			echo "<select name='cityname'>";
			while($row = mysqli_fetch_array($result)) {
				echo "<option value='" . $row["city_name"]. "'>" . $row["city_name"]. "</option>";
			}
			echo "</select>";
		} else {
			echo "0 results";
		}
		//mysqli_close($conn);
	?>
<input type="submit" value="Submit">
</form>
<form action="showmarketsalesinfo.php" method="post">
	<?php


		//$conn = mysqli_connect($servername, $username, $password, $dbname);

		if (!$conn) {
			die("Connection failed: " . mysqli_connect_error());
		} 


		$space = " ";
		$sql = 
		   "SELECT MARKET.market_name as MarketName  , MARKET.market_id as MarketID, CITY.city_name as CityName FROM MARKET
			JOIN CITY
			ON CITY.city_id = MARKET.city_id";
		$result = mysqli_query($conn,$sql);

		if (mysqli_num_rows($result) > 0) {
			echo "Select a market: </br>";
			echo "<select name='marketname'>";
			while($row = mysqli_fetch_array($result)) {
				echo "<option value=' ". $row["CityName"]." ".$space." " . $row["MarketID"]. "'>  ". $row["CityName"]." ". $row["MarketName"]. " </option>";
			}
			echo "</select>";
		} else {
			echo "0 results";
		}
		//mysqli_close($conn);
	?>
<input type="submit" value="Submit">
</form>

<br/>

<form action="report.php" method="post">
  Click to get visualized report for district and market.<br>
  <br>
  <input type="submit" value="Get Report">
</form> 
<br/>


</body>
</html>

