<?php
	include("pChart2.1.4/class/pData.class.php");
	include("pChart2.1.4/class/pDraw.class.php");
	include("pChart2.1.4/class/pPie.class.php");
	include("pChart2.1.4/class/pImage.class.php");
	
	$servername = "localhost";
	$username = "root";
	$password = "mysql";
	
	$dbname = "berkay_kocak";
	
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}
	$dataArray3 = array();
	$sql = "SELECT MARKET.market_name, SUM(PRODUCT.price*SALE.amount)
			FROM SALE
			JOIN SALESMAN
			ON SALESMAN.salesman_id = SALE.salesman_id
			JOIN PRODUCT
			ON PRODUCT.product_id = SALE.product_id
			JOIN MARKET
			ON MARKET.market_id = SALESMAN.market_id
			JOIN CITY
			ON CITY.city_id = MARKET.city_id
			WHERE CITY.city_name = '".$_POST["cityname"]."'
			GROUP BY MARKET.market_id;";
	
	$result = mysqli_query($conn,$sql);
	
	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_array($result)) {
			$marketname = $row["market_name"];
			$earnings = $row["SUM(PRODUCT.price*SALE.amount)"];
			$dataArray3[$marketname] = $earnings;
		}
	}
	
	$keys2 = array_keys($dataArray3);
	$MyData2 = new pData();   
	$MyData2->addPoints($dataArray3,"Earnings");  
	$MyData2->setSerieDescription("Earnings","Application A");
	 
	$MyData2->addPoints($keys2,"Markets");
	$MyData2->setAbscissa("Markets");
	 
	$myPicture2 = new pImage(700,230,$MyData2,TRUE);
	 
	$Settings2 = array("R"=>173, "G"=>152, "B"=>217, "Dash"=>1, "DashR"=>193, "DashG"=>172, "DashB"=>237);
	$myPicture2->drawFilledRectangle(0,0,700,230,$Settings2);
	 
	$Settings2 = array("StartR"=>209, "StartG"=>150, "StartB"=>231, "EndR"=>111, "EndG"=>3, "EndB"=>138, "Alpha"=>50);
	$myPicture2->drawGradientArea(0,0,700,230,DIRECTION_VERTICAL,$Settings2);
	$myPicture2->drawGradientArea(0,0,700,20,DIRECTION_VERTICAL,array("StartR"=>0,"StartG"=>0,"StartB"=>0,"EndR"=>50,"EndG"=>50,"EndB"=>50,"Alpha"=>100));
	 
	$myPicture2->drawRectangle(0,0,699,229,array("R"=>0,"G"=>0,"B"=>0));
	 
	$myPicture2->setFontProperties(array("FontName"=>"pChart2.1.4/fonts/Silkscreen.ttf","FontSize"=>6));
	$myPicture2->drawText(10,13,"3D Pie Chart",array("R"=>255,"G"=>255,"B"=>255));
	 
	$myPicture2->setFontProperties(array("FontName"=>"pChart2.1.4/fonts/Forgotte.ttf","FontSize"=>10,"R"=>80,"G"=>80,"B"=>80));
	  
	$PieChart = new pPie($myPicture2,$MyData2);
	 
	$PieChart->setSliceColor(0,array("R"=>143,"G"=>197,"B"=>0));
	$PieChart->setSliceColor(1,array("R"=>97,"G"=>77,"B"=>63));
	$PieChart->setSliceColor(2,array("R"=>97,"G"=>113,"B"=>63));
	 
	  
	$myPicture2->setShadow(TRUE,array("X"=>3,"Y"=>3,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));
	 
	$PieChart->draw3DPie(340,125,array("WriteValues"=>TRUE,"DataGapAngle"=>10,"DataGapRadius"=>6,"Border"=>TRUE));
	 
	$myPicture2->setFontProperties(array("FontName"=>"pChart2.1.4/fonts/pf_arma_five.ttf","FontSize"=>6));
	$myPicture2->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>20));
	$myPicture2->drawText(340,200,"Total Earnings for Markets in ".$_POST["cityname"]."",array("DrawBox"=>TRUE,"BoxRounded"=>TRUE,"R"=>0,"G"=>0,"B"=>0,"Align"=>TEXT_ALIGN_TOPMIDDLE));
	 
	$myPicture2->setFontProperties(array("FontName"=>"pChart2.1.4/fonts/Silkscreen.ttf","FontSize"=>6,"R"=>255,"G"=>255,"B"=>255));
	$PieChart->drawPieLegend(250,8,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL));
	
	$myPicture2->Render("piechart.png");


	$dataArray1 = array();
	$dataArray2 = array();
	$sql = "SELECT m1.Market, m1.total_max, m2.total_min FROM (
			SELECT * FROM(SELECT MARKET.market_id AS ID, MARKET.market_name AS Market, PRODUCT.price*SALE.amount AS total_max, CONCAT(SALESMAN.salesman_firstname,' ',SALESMAN.salesman_lastname) AS Salesman
			FROM SALE
			JOIN PRODUCT
			ON PRODUCT.product_id = SALE.product_id
			JOIN SALESMAN
			ON SALESMAN.salesman_id = SALE.salesman_id
			JOIN MARKET
			ON MARKET.market_id = SALESMAN.market_id
			JOIN CITY
			ON CITY.city_id = MARKET.city_id
			WHERE CITY.city_name = '".$_POST["cityname"]."'
			ORDER BY total_max DESC) AS s1
			GROUP BY s1.ID) AS m1		 
			 JOIN (			 
			SELECT * FROM(SELECT MARKET.market_id AS ID, MARKET.market_name AS Market, PRODUCT.price*SALE.amount AS total_min, CONCAT(SALESMAN.salesman_firstname,' ',SALESMAN.salesman_lastname) AS Salesman
			FROM SALE
			JOIN PRODUCT
			ON PRODUCT.product_id = SALE.product_id
			JOIN SALESMAN
			ON SALESMAN.salesman_id = SALE.salesman_id
			JOIN MARKET
			ON MARKET.market_id = SALESMAN.market_id
			JOIN CITY
			ON CITY.city_id = MARKET.city_id
			WHERE CITY.city_name = '".$_POST["cityname"]."'
			ORDER BY total_min ASC) AS s1
			GROUP BY s1.ID) AS m2
			ON m1.ID = m2. ID;";
	
	$result = mysqli_query($conn,$sql) or die("NO!");
	
	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_array($result)) {
			$marketname = $row["Market"];
			$max = $row["total_max"];
			$min = $row["total_min"];
			$dataArray1[$marketname] = $max;
			$dataArray2[$marketname] = $min;
		}
	}
	$keys = array_keys($dataArray1);
	$MyData = new pData();  
	$MyData->addPoints($dataArray1,"Max Sale");
	$MyData->addPoints($dataArray2,"Min Sale");
	$MyData->setAxisName(0,"Amount(TL)");
	 
	$MyData->addPoints($keys,"Markets");
	$MyData->setSerieDescription("Markets","Market");
	$MyData->setAbscissa("Markets");
	 
	$myPicture = new pImage(800,300,$MyData);
	 
	$myPicture->drawGradientArea(0,0,800,300,DIRECTION_VERTICAL,array("StartR"=>240,"StartG"=>240,"StartB"=>240,"EndR"=>180,"EndG"=>180,"EndB"=>180,"Alpha"=>100));
	$myPicture->drawGradientArea(0,0,800,300,DIRECTION_HORIZONTAL,array("StartR"=>240,"StartG"=>240,"StartB"=>240,"EndR"=>180,"EndG"=>180,"EndB"=>180,"Alpha"=>20));
	 
	$myPicture->setFontProperties(array("FontName"=>"pChart2.1.4/fonts/pf_arma_five.ttf","FontSize"=>6));
	 
	$myPicture->setGraphArea(50,30,780,270);
	$myPicture->drawScale(array("CycleBackground"=>TRUE,"DrawSubTicks"=>TRUE,"GridR"=>0,"GridG"=>0,"GridB"=>0,"GridAlpha"=>10));
	 
	$myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));
	 
	$settings = array("Gradient"=>TRUE, "DisplayPos"=>LABEL_POS_INSIDE, "DisplayValues"=>TRUE, "DisplayR"=>255, "DisplayG"=>255, "DisplayB"=>255, "DisplayShadow"=>TRUE, "Surrounding"=>30);
	$myPicture->drawBarChart($settings);
	 
	$myPicture->drawLegend(650,12,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL));
	
	$myPicture->Render("barchart.png");

?>
<!DOCTYPE html>
<html>
<body>
<h1> 
	Visualized Analyse of City Based Sales
</h1>
<img src="piechart.png">

<img src="barchart.png">

</body>
</html>