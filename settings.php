<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
<meta name="oiltracker1" content="project 2013">
<link rel="stylesheet" type="text/css" href="styles/main.css" />
<link rel="stylesheet" type="text/css" href="styles/toggle_switch.css">
<title>Oiltracker1 - Settings</title>

<script src="js/jquery.js" type="text/javascript"></script>
<script src="highcharts/js/highcharts.js"></script>
<script src="highcharts/js/themes/gray.js"></script>
<script src="js/settings.js" type="text/javascript"></script>
<script type="text/javascript">

	console.log(" ** document ready ** ");
	var refresh_rate = 600;
	
	jQuery(document).ready(function() {

		populateFields();
		updateTankStats();
		
		updateMaxLitres();
		toggleProfile();
		updateFuel();
		updateInterval();

		reScan();

		function reScan() {

			if($('#profile_toggle').is(':checked')){
				updateMaxLitres();
				console.log(" ** RESCANNING ** ");
			}

			setTimeout(reScan, refresh_rate);
		}	


	});
</script>

</head>
<body>

	<div id="wrapper">

		<div id="header"></div>
		<div id="header_line"></div>

		<div id="page_left">
		
			<tankStatus id="tank_stats">
				<fieldset>
					<legend>TANK STATS</legend>	
					<p>
						PROFILE STATUS:
						<input style="margin-left: 41px; background-color: #B6B6B6;" type="number" id="profileStatus" readonly>
					</p>
					<p>
						TANK CAPACITY:
						<input style="margin-left: 50px; background-color: #B6B6B6;" type="number" id="tankCapacity" readonly>
					</p>					
					<p>
						RANGE WHEN FULL:
						<input style="margin-left: 20px; background-color: #B6B6B6;" type="number" id="rangeFull" readonly>
					</p>	
					<p>
						RANGE WHEN EMPTY:
						<input style="margin-left: 5px; background-color: #B6B6B6;" type="number" id="rangeEmpty" readonly>
					</p>
				</fieldset>
			</tankStatus>
		
		</div>

		<div id="page_center">

			<form id="profile_settings">
				<fieldset>
					<legend>PROFILE SETTINGS</legend>
					<label class="checkbox toggle android" onclick=""> <input id="profile_toggle" type="checkbox" />
						<p>
							TOGGLE PROFILE MODE: <span>ON</span> <span>OFF</span>
						</p> <a class="slide-button"></a>
					</label>

					<p>
						LITRE(S) PROFILED: 
						<input style="margin-left: 140px; background-color: #B6B6B6;" type="number" id="litresProfiled" readonly>
					</p>
				</fieldset>
			</form>

			<form id="fuel_settings">
				<fieldset>
					<legend>FUEL SETTINGS</legend>
					<p>
						CURRENT FUEL PRICE: 
						<input style="margin-left: 120px; background-color: #B6B6B6;" type="number" value="" id="currentPrice" readonly>
					</p>
					<p>
						NEW FUEL PRICE: 
						<input style="margin-left: 158px;" type="number" id="newPrice">
					</p>
					<p>
						<input type="submit" value="UPDATE &uarr;" id="updatePrice">
					</p>
				</fieldset>
			</form>


			<form id="temp_settings">
				<fieldset>
					<legend>FUEL SETTINGS</legend>
					<p>
						CURRENT READ TIME: 
						<input style="margin-left: 120px; background-color: #B6B6B6;" type="number" id="currentReadTime" readonly>
					</p>
					<p>
						NEW READ TIME: 
						<input style="margin-left: 158px;" type="number" id="newReadTime">
					</p>
					<p>
						<input type="submit" value="UPDATE &uarr;">
					</p>
				</fieldset>

			</form>

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
