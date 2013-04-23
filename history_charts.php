<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
	<meta name="oiltracker1" content="project 2013">
	<link rel="stylesheet" type="text/css" href="styles/main.css"/>
	<title>Oiltracker1 - History</title>
	 
	<script src="js/jquery.js"></script>	
	<script src="highstock/js/highstock.js"></script>
	
	<script src="js/charts.js" type="text/javascript"></script>
	<script src="highcharts/js/themes/gray.js"></script>
	
	<script type="text/javascript">

	console.log(" ** document ready ** ");
	
	jQuery(document).ready(function() {
		
		load_HS_chart("temp", "history");
		load_HS_chart("flow", "history");
		load_HS_chart("levl", "history");
				    
	});
	</script>

</head>
<body>

	<div id="wrapper">
	
		<div id="header"></div>
		<div id="header_line"></div>
	
		<div id="page_left">
			<div id="temp_settings"></div>
			<div id="flow_settings"></div>
			<div id="levl_settings"></div>
		</div>
		
		<div id="page_center">
			<div id="levl_container"></div>
			<div id="flow_container"></div>
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