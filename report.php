<?

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
$sql = "SELECT DISTRICT.district_name , SUM(PRODUCT.price*SALE.amount)
        FROM SALE
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
        GROUP BY DISTRICT.district_id";

$result = mysqli_query($conn,$sql);

if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_array($result)) {
        $districtname = $row["district_name"];
        $earnings = $row["SUM(PRODUCT.price*SALE.amount)"];
        $dataArray3[$districtname] = $earnings;
    }
}

$keys2 = array_keys($dataArray3);
$MyData2 = new pData();   
$MyData2->addPoints($dataArray3,"Earnings");  
$MyData2->setSerieDescription("Earnings","Application A");
 
$MyData2->addPoints($keys2,"Districts");
$MyData2->setAbscissa("Districts");
 
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
$myPicture2->drawText(340,200,"Total Earnings for Districts ",array("DrawBox"=>TRUE,"BoxRounded"=>TRUE,"R"=>0,"G"=>0,"B"=>0,"Align"=>TEXT_ALIGN_TOPMIDDLE));
 
$myPicture2->setFontProperties(array("FontName"=>"pChart2.1.4/fonts/Silkscreen.ttf","FontSize"=>6,"R"=>255,"G"=>255,"B"=>255));
$PieChart->drawPieLegend(250,8,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL));

$myPicture2->Render("piechart_districts.png");

?>

<?

$servername = "localhost";
$username = "root";
$password = "mysql";

$dbname = "berkay_kocak";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$dataArray3 = array();
$sql = "SELECT DISTINCT(MARKET.market_name) , SUM(PRODUCT.price*SALE.amount)
        FROM SALE
        JOIN SALESMAN
        ON SALESMAN.salesman_id = SALE.salesman_id
        JOIN PRODUCT
        ON PRODUCT.product_id = SALE.product_id
        JOIN MARKET
        ON MARKET.market_id = SALESMAN.market_id
        GROUP BY MARKET.market_name";

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
 
$MyData2->addPoints($keys2,"Districts");
$MyData2->setAbscissa("Districts");
 
$myPicture2 = new pImage(700,230,$MyData2,TRUE);
 
$Settings2 = array("R"=>173, "G"=>152, "B"=>217, "Dash"=>1, "DashR"=>193, "DashG"=>172, "DashB"=>237);
$myPicture2->drawFilledRectangle(0,0,1000,500,$Settings2);
 
$Settings2 = array("StartR"=>500, "StartG"=>150, "StartB"=>231, "EndR"=>111, "EndG"=>3, "EndB"=>138, "Alpha"=>50);
$myPicture2->drawGradientArea(0,0,1000,500,DIRECTION_VERTICAL,$Settings2);
$myPicture2->drawGradientArea(0,0,1000,20,DIRECTION_VERTICAL,array("StartR"=>0,"StartG"=>0,"StartB"=>0,"EndR"=>50,"EndG"=>50,"EndB"=>50,"Alpha"=>100));
 
$myPicture2->drawRectangle(0,0,1000,500,array("R"=>0,"G"=>0,"B"=>0));
 
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
$myPicture2->drawText(340,200,"Total Earnings for Markets ",array("DrawBox"=>TRUE,"BoxRounded"=>TRUE,"R"=>0,"G"=>0,"B"=>0,"Align"=>TEXT_ALIGN_TOPMIDDLE));
 
$myPicture2->setFontProperties(array("FontName"=>"pChart2.1.4/fonts/Silkscreen.ttf","FontSize"=>6,"R"=>255,"G"=>255,"B"=>255));
$PieChart->drawPieLegend(250,8,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL));

$myPicture2->Render("piechart_markets.png");

?>


<!DOCTYPE html>
<html>
<body>
<h1>All sales divided into Markets </h1>
<img src="piechart_districts.png">
<h1>All sales divided into Markets </h1>
<img src="piechart_markets.png">
</body>
</html>