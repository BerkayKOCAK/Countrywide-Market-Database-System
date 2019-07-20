
<?
$servername = "localhost";
	$username = "root";
	$password = "mysql";
	$dbname = "berkay_kocak";
	
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}

$sql = "SELECT CONCAT(SALESMAN.salesman_firstname,' ',SALESMAN.salesman_lastname) as salesman,  PRODUCT.product_name as product, SALE.amount as amount, CONCAT(SUM(PRODUCT.price*SALE.amount),' TL')
			FROM SALE
			JOIN SALESMAN
			ON SALESMAN.salesman_id = SALE.salesman_id
			JOIN PRODUCT
			ON PRODUCT.product_id = SALE.product_id
			JOIN MARKET
			ON MARKET.market_id = SALESMAN.market_id
			WHERE SALESMAN.salesman_id = '".$_POST["salesmanID"]."'
			GROUP BY PRODUCT.product_name";
	
	$result = mysqli_query($conn,$sql);
	
	if (mysqli_num_rows($result) > 0) {
		echo "Salesman sales info for salesman with ID ".$_POST["salesmanID"]."</br>";
		echo "<table border='1'>";
		echo "<tr><td>Salesman</td><td>Product</td><td>Total Amount</td><td>Total Price</td></tr>";
		while($row = mysqli_fetch_array($result)) {
			echo    "<tr>";
            echo    "<td>"  .$row["salesman"]."</td> 
                    <td>"   .$row["product"]. "</td> 
                    <td>"   .$row["amount"]. "</td>
                    <td>"   .$row["CONCAT(SUM(PRODUCT.price*SALE.amount),' TL')"]. "</td>";
		echo        "</tr>";
		}
		echo "</table>";
	} else {
		echo "0 results" .$_POST["salesmanID"];
    }
?>