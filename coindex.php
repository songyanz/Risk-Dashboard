<?php
	$db_hostname='localhost';
	$db_database='appliedis';
	$db_username='root';
	$db_password='123456';

	$conn = new mysqli($db_hostname, $db_username, $db_password, $db_database);

	if ($conn->connect_error)
	{
		echo "Failed to connect to MySQL: " . $conn->connect_error;
	}

	$sectora = $_POST['sectora'];
	$sectorb = $_POST['sectorb'];
				
	$array1 = array();
	$array2 = array();
	$array3 = array();

	$query = "SELECT * FROM coindex";

	if ($result = $conn->query($query)) {					
		while($row = $result->fetch_assoc()) {
			$array1[] = $row[$sectora];
			$array2[] = $row[$sectorb];
		}
	}

	$sum1 = 0;
	$sum2 = 0;
	$summulti = 0;
	$sumq1 = 0;
	$sumq2 = 0;

	for ($j=0; $j < 22; $j++) { 
		$sum1 = $sum1 + $array1[$j];
		$sum2 = $sum2 + $array2[$j];
		$summulti = $summulti + $array1[$j] * $array2[$j];
		$sumq1 = $sumq1 + $array1[$j] ** 2;
		$sumq2 = $sumq2 + $array2[$j] ** 2;
	}

	$r = (22*$summulti-$sum1*$sum2)/(sqrt(22*$sumq1-$sum1**2)*sqrt(22*$sumq2-$sum2**2));

	for ($i=0; $i < 22; $i++) { 
		$array3[$i][0] = $array1[$i];
		$array3[$i][1] = $array2[$i];
	}

	$data = json_encode($array3);

	$X = $array1;
	$Y = $array2;
	$xavg = array_sum($X)/count($X); 
    $yavg = array_sum($Y)/count($Y); 
    $XMD = Array();
    $YMD = Array();
    $mdcross_sum = 0;
    $xdif_square_sum = 0;
    $count = count($X);
    for ($k=0; $k<$count; $k++) {
        $xdif = (float)$X[$k]-$xavg;
        $ydif = (float)$Y[$k]-$yavg;
        $XMD[$k] = $xdif;
        $YMD[$k] = $ydif;
        $mdcross_sum += $xdif*$ydif;
        $xdif_square_sum += pow($xdif, 2);
    } 
    $b = $mdcross_sum/$xdif_square_sum;
    $a = $yavg-$b*$xavg;

	$conn->close();
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Correlation</title>
		<link href="css/style.css" type="text/css" rel="stylesheet">
        <script type="text/javascript" src="incubator-echarts-4.2.1/dist/echarts.min.js"></script>
		<script type="text/javascript" src="echarts-stat-master/dist/ecStat.js"></script>
		<script type="text/javascript" src="/js/jquery-3.3.1.min.js"></script>
		<script type="text/javascript" src="js/risk.js"></script>
	</head>

	<body>
		<div class="banner">
			<div class="banner1">
				<a href="index.html"><img src="images/carey.png" alt="can't display" title="Carey Business School" width="430px" height="100px" ></a>
			</div>
			<div class="banner2">
				<div style="height: 50px;"></div>
				<div class="banner3">
					<nav>
						<ul>
							<li><a href="index.html">Home</a>
							<li><a href="weight.html">Weight</a></li>
							<li><a href="VTotal.html">History Volatility</a></li>
							<li><a href="risk.html">Future Risk</a></li>
							<li><a href="correlation.html">Correlation</a></li>
						</ul>
					</nav>
				</div>	
			</div>
		</div>
        <br>
        <div class="back" style="width: 12%; margin-left: 10%">
        	<input type="button" name="back" value="Back" onclick="window.location.href = 'coindex.html'">
        </div>
        <div style="text-align: center;">
        	<?php echo "y = ".number_format($a, 3)." + ".number_format($b, 3)." x <br>" ?>
        	<?php echo "r = ".number_format($r, 3); ?>
        	<?php echo "<h3>".$sectora." - ".$sectorb." Correlation</h3>"; ?>
        </div>
		<div style="width: 70%; margin: auto;">
			<?php echo $sectorb; ?>
		</div>
        <div id="scatter" style="width: 80%; height:600px; margin: auto; display: block;"></div>
        <div style="width: 70%; margin: auto; text-align: right;">
			<?php echo $sectora; ?>
		</div>
        <script>
            var dom = document.getElementById("scatter");
            var myChart = echarts.init(dom);
            var app = {};
            var data = eval(<?php echo json_encode($array3);?>);
            option = null;
            option = {
                xAxis: {
                },
                yAxis: {
                },
                series: [{
                    symbolSize: 20,
                    data: data,
                    type: 'scatter',
                }]
            };
            ;
            if (option && typeof option === "object") {
                myChart.setOption(option, true);
            }
       </script>


		<footer id="copyright" align="center">&copy: 2019 Carey</footer>
	</body>
</html>