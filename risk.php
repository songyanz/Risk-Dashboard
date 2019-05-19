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

	$sector = $_POST['sector'];
	$period = $_POST['period'];
	$price = $_POST['price'];
	$rate = $_POST['rate'];
	$select = $_POST['select'];

	$array = array();
	$data = array();
	$count = 0;

	$query = "SELECT * FROM $period";

	if ($result = $conn->query($query)) {					
		while($row = $result->fetch_assoc()) {
			$array[] = $row[$sector];
		}
	}
		
	for ($i=1; $i < 1001; $i++) { 
		$data[$i-1] = $array[$i];
		if ($select=='price') {
			if ($array[$i]>$price) {
				$count++;
			}
		} elseif ($select=='rate') {
			$price = $array[0] * (1 + $rate / 100);
			if ($array[$i]>$price) {
				$count++;
			}
		}
	}

	$pos = $count / 10;

	$line = $price;
	$conn->close();
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Risk</title>
		<link href="css/style.css" type="text/css" rel="stylesheet">
        <script type="text/javascript" src="js/echarts.js"></script>
		<script type="text/javascript" src="js/ecStat.js"></script>
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
        	<input type="button" name="back" value="Back" onclick="window.location.href = 'risk.html'">
        </div>
        <div style="text-align: center;">
        	<?php
        		if ($select=='price') {
        			echo "The probability that the price of ".$sector." is higher than the expected price after a ".$period." is ".$pos."%";
        		} else {
        			echo "The probability that the price of ".$sector." increase higher than ".$rate."% after a ".$period." is ".$pos."%";
        		}
 
        	?>
        </div>
        <div style="width: 70%; margin: auto; text-align: center;">
			<?php echo "<h3>".$sector." Simulation Distribution</h3>";?>
		</div>
        <div id="hist" style="width: 80%; height:600px; margin: auto; display: block;"></div>

        <script>
        	var dom = document.getElementById("hist");
            var myChart = echarts.init(dom);
			var ddd = eval(<?php echo json_encode($data);?>);
			var mark = eval(<?php echo json_encode($line);?>);


			var bins = ecStat.histogram(ddd);

			var option = {
			    /*title: {
			        text: 'Simulation Distribution',
			        left: 'center',
			        top: 20
			    },*/
			    color: ['rgb(25, 183, 207)'],
			    grid: {
			        left: '3%',
			        right: '3%',
			        bottom: '3%',
			        containLabel: true
			    },
			    xAxis: [{
			        type: 'value',
			        scale: true,
			    }],
			    yAxis: [{
			        type: 'value',
			    }],
			    series: [{
			        name: 'height',
			        type: 'bar',
			        barWidth: '99.3%',
			        label: {
			            normal: {
			                show: true,
			                position: 'insideTop',
			                formatter: function(params) {
			                    return params.value[1];
			                }
			            }
			        },
			        data: bins.data,
					markLine: {
				        itemStyle: {
				            normal: { lineStyle: { type: 'solid', color: '#000' }, label: { show: true, position: 'end' } },
				        },
				        data: [
				            {
				                xAxis: mark,
				            }
				        ]
				    },
			    }]
			};
            if (option && typeof option === "object") {
                myChart.setOption(option, true);
            }
       </script>


		<footer id="copyright" align="center">&copy: 2019 Carey</footer>
	</body>
</html>