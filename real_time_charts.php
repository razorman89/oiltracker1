<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
	<meta name="oiltracker1" content="project 2013">
	<link rel="stylesheet" type="text/css" href="styles/main.css"/>
	<title>Oiltracker1 - Real time</title>
	 
	<script src="js/jquery.js"></script>
	<script src="highcharts/js/highcharts.js"></script>
	<script src="highcharts/js/highcharts-more.js"></script>
	
	<!-- 
	<script src="highcharts/js/highcharts.js" type="text/javascript"></script>
	<script src="highcharts/js/modules/exproting.js" type="text/javascript"></script>
	-->

	<script src="js/jquery.js" type="text/javascript"></script>
	<script src="js/charts.js" type="text/javascript"></script>
	<script src="js/predictions.js" type="text/javascript"></script>
	
	<script src="highcharts/js/themes/gray.js"></script>
	<script src="js/gauges.js" type="text/javascript"></script>
	
	<script type="text/javascript">

	console.log(" ** document ready ** ");
	var refresh_rate = 2000;
	var maxLitres = 0;
	
	jQuery(document).ready(function() {

		$.post('php/get_data.php/', {
			dataType : "levl",
			actionType : "var_init"
			
		}, function(data) {
			
			if (data.status == 'ok') {
				console.log(" ** DATA STATUS: ok ** ");
				maxLitres = data.maxLitres;
				console.log(" ** system 'MaxLitres' equals:" + maxLitres + " ** ");
			}
			
			else {
				console.log(" ** data status: corrupt / not set ** ");
			}
			
		}, "json").done(

		function(){
			draw_RT_charts(maxLitres);
			draw_RT_gauges(maxLitres);
			reScan();

			function reScan() {
				
				console.log(" ** RESCANNING CHART DATA ** ");
				update_RT_chart(gaugeRT_temp, "temp", "update", "temp_gauge", maxLitres);
				update_RT_chart(gaugeRT_levl, "levl", "update", "levl_gauge");
				update_RT_chart(chartRT_temp, "temp", "update", "temp_chart");
				update_RT_chart(chartRT_flow, "flow", "update", "flow_chart");
				update_RT_chart(chartRT_levl, "levl", "update", "levl_chart");
				make_ST_predictions();
				setTimeout(reScan, refresh_rate);
			}	
		});
	});
	</script>

</head>
<body>

	<div id="wrapper">
	
		<div id="header"></div>
		<div id="header_line"></div>
		
		<div id="page_left">
			<div id="prediction_container">
				<predictionStats id="tank_stats">
	
					<p>Weekly Cost:
						<br>
						<input type="number" id="avgCost" readonly>
					</p>
					
					<p>Weely Usage:
						<br>
						<input type="number" id="avgUsage" readonly>	
					</p>				
					
					<p>Tank Fill Date:	
						<br>
						<input type="number" id="tankFilledDate" readonly>
					</p>
					
					<p>Predicted Refill Date:
						<br>
						<input type="number" id="tankemptyDate" readonly>
					</p>
						
				</predictionStats>
			</div>
			<div id="current_level_container"></div>
			<div id="current_temp_container"></div>
		</div>
		
		<div id="page_center">
			<div id="flow_container"></div>
			<div id="levl_container"></div>
			<div id="temp_container"></div>
		</div>
		
		<div id="nav_bar">
			<ul id="panel">
		        <li class="animation"><a href="index.php">Welcome</a></li>
		       	<li class="animation"><a href="comming_soon.php">About Project</a></li>
		       	<li class="animation"><a href="real_time_charts.php">Real Time Charts</a></li>
		        <li class="animation"><a href="history_charts.php">History Charts</a></li>
		        <li class="animation"><a href="settings.php">System Settings</a></li>
		    </ul>
		</div>
		
		<div id="footer_line"></div>	
		<div id="footer_div">THIS IS A FOOTER</div>	
		
	</div>

</body>
</html>