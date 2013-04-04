<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
	<meta name="oiltracker1" content="project 2013">
	<link rel="stylesheet" type="text/css" href="main.css"/>
	<title>Oiltracker1 - Real time</title>
	 
	<script src="js/jquery.js"></script>
	<script src="highcharts/js/highcharts.js"></script>
	
	<!-- 
	<script src="highcharts/js/highcharts.js" type="text/javascript"></script>
	<script src="highcharts/js/modules/exproting.js" type="text/javascript"></script>
	-->
	
	<script src="js/jquery.js" type="text/javascript"></script>
	<script src="js/charts.js" type="text/javascript"></script>
	<script src="highcharts/js/themes/gray.js"></script>
	
	<script type="text/javascript">

	console.log(" ** document ready ** ");
	
	jQuery(document).ready(function() {
		var refresh_rate = 1000;
		
		draw_RT_charts();
		reScan();
		
		function reScan() {
			console.log(" ** RESCANNING CHART DATA ** ");
			
			update_RT_chart("temp", chartRT_temp, "update");
			update_RT_chart("flow", chartRT_flow, "update");
			update_RT_chart("levl", chartRT_levl, "update");
			setTimeout(reScan, refresh_rate);

		}
		    
	});
	</script>

</head>
<body>

	<div id="wrapper">
	
		<div id="header"></div>
		<div id="header_line"></div>
	
		<div id="page_left"></div>
		
		<div id="page_center">
			<div id="temp_container"></div>
			<div id="flow_container"></div>
			<div id="levl_container"></div>
		</div>
		
		<div id="nav_bar">
			<ul id="panel">
		        <li class="animation"><a href="index.php">Welcome</a></li>
		       	<li class="animation"><a href="comming_soon.php">About Project</a></li>
		       	<li class="animation"><a href="real_time_charts.php">Real Time Charts</a></li>
		        <li class="animation"><a href="history_charts.php">History Charts</a></li>
		        <li class="animation"><a href="comming_soon.php">Prediction Charts</a></li>
		    </ul>
		</div>
		
		<div id="footer_line"></div>	
		<div id="footer_div">THIS IS A FOOTER</div>	
		
	</div>

</body>
</html>