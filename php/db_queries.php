<?php

function getInitTemp($db){
	$query_tme = mysqli_query($db, "SELECT * FROM (SELECT `tempid`, `tstamp`, `tempdc` FROM `temp` ORDER BY `tempid` DESC LIMIT 10) holder ORDER BY `tempid` ASC;");
	//print_r($query_tme);

	$return_arr = array();

	while($item = mysqli_fetch_assoc($query_tme)) {


		$t = array();

		$float_dat = floatval($item['tstamp']);
		$float_val = floatval($item['tempdc']);

		$t[] = $float_dat;
		$t[] = $float_val;

		$return_arr[] = $t;
	}

	return $return_arr;
}

function getInitFlow($db){
	$query_tme = mysqli_query($db, "SELECT * FROM (SELECT `statsid`, `tstamp`, `ltrspm`, `costpm` FROM `stats` ORDER BY `statsid` DESC LIMIT 6) holder ORDER BY `statsid` ASC;");

	$return_arr_ltrs = array();
	$return_arr_cost = array();

	while($item = mysqli_fetch_assoc($query_tme)) {


		$ret_ltrs = array();
		$ret_cost = array();

		$float_dat = floatval($item['tstamp']);
		$float_ltr = floatval($item['ltrspm']);
		$float_cst = floatval($item['costpm']);

		$ret_ltrs[] = $float_dat;
		$ret_ltrs[] = $float_ltr;
		
		$ret_cost[] = $float_dat;
		$ret_cost[] = $float_cst;

		$return_arr_ltrs[] = $ret_ltrs;
		$return_arr_cost[] = $ret_cost;
	}
	
	return json_encode(array('status' => 'ok', 'series_ltrs_data' => $return_arr_ltrs, 'series_cost_data' => $return_arr_cost));

	//return $return_arr;
}
//***************INITIAL LEVEL
function getInitLevl($db){	
	//$query_stats = mysqli_query($db, "SELECT `statsid`, `tstamp`, `currentLevel` FROM stats ORDER BY `statsid` DESC LIMIT 1");
	$query_range = mysqli_query($db, "SELECT `rangeid`, `tstamp`, `currentLevel` FROM `range` ORDER BY `rangeid` DESC LIMIT 1");
	
	$return_arr = array();
	$t = array();
	
	while($item = mysqli_fetch_assoc($query_stats)) {
	
		$xvalue  = floatval($item['tstamp']);
		$currentLevel  = floatval($item['currentLevel']);
		
		$t[] = $xvalue;
	
	}
	
	$query_profile = mysqli_query($db, "SELECT AVG(`litres`) AS `avglitres` FROM `profile` WHERE `range` = '$currentLevel'");
	
	while ($item = mysqli_fetch_assoc($query_profile)) {
	
		$yvalue = floatval($item['avglitres']);
		
		$t[] = $yvalue;
	}	
	
	$return_arr[] = $t;
	return $return_arr;
	
}

function getLastTemp($db){
	$query_tme = mysqli_query($db, "SELECT `tempid`, `tstamp`, `tempdc` FROM `temp` ORDER BY `tempid` DESC LIMIT 1");
	
	while($item = mysqli_fetch_assoc($query_tme)) {
		
		$int_index = intval($item['tempid']);
		$xvalue  = floatval($item['tstamp']);
		$yvalue  = floatval($item['tempdc']);
		
	}
	
	return $return_arr = json_encode(array('status' => 'ok', 'index' => $int_index, 'xvalue' => $xvalue, 'yvalue' => $yvalue));	
}

function getLastFlow($db){
	$query_tme = mysqli_query($db, "SELECT `statsid`, `tstamp`, `ltrspm`, `costpm` FROM `stats` ORDER BY `statsid` DESC LIMIT 1");

	while($item = mysqli_fetch_assoc($query_tme)) {

		$int_index = intval($item['statsid']);
		$xvalue  = floatval($item['tstamp']);
		$yvalue  = floatval($item['ltrspm']);
		$costvalue  = floatval($item['costpm']);

	}

	return $return_arr = json_encode(array('status' => 'ok', 'index' => $int_index, 'xvalue' => $xvalue, 'yvalue' => $yvalue, 'costvalue' => $costvalue));
}
//***************LATEST LEVEL
function getLatestLevl($db){
	
	//$query_stats = mysqli_query($db, "SELECT `statsid`, `tstamp`, `currentLevel` FROM stats ORDER BY `statsid` DESC LIMIT 1");
	$query_range = mysqli_query($db, "SELECT `rangeid`, `tstamp`, `currentlevel` FROM `range` ORDER BY `rangeid` DESC LIMIT 1");
	while($item = mysqli_fetch_assoc($query_range)) {

		$time_stamp  = floatval($item['tstamp']);
		$currentLevel  = floatval($item['currentlevel']);

	}
	
	$query_profile_max = mysqli_query($db, "SELECT `litres`, `range` FROM `profile` ORDER BY `profileid` DESC LIMIT 1");
	while ($item = mysqli_fetch_assoc($query_profile_max)) {
	
		$max_litres = floatval($item['litres']);
		$max_range = floatval($item['range']);
	}
	
	$query_profile_min = mysqli_query($db, "SELECT `range` FROM `profile` ORDER BY `profileid` ASC LIMIT 1");
	while ($item = mysqli_fetch_assoc($query_profile_max)) {
	
		$min_range = floatval($item['range']);
	}
	
	$query_profile_avg = mysqli_query($db, "SELECT AVG(`litres`) AS `avgused` FROM `profile` WHERE `range` = '$currentLevel'");
	while ($item = mysqli_fetch_assoc($query_profile_avg)) {
		
		$avg_used = floatval($item['avgused']);
	}
	
	if($currentLevel >= $min_range AND $currentLevel <= $max_range) {
		$avg_left = $max_litres - $avg_used;
	}
	
	else if($currentLevel < $min_range) {
		$avg_left = $max_litres;
	}
	
	else if ($currentLevel > $max_range) {
		$avg_left = 0.0;
	}
	
	$avg_left = floatval(number_format((float)$avg_left, 2, '.', ''));
	
	return $return_arr = json_encode(array('status' => 'ok', 'xvalue' => $time_stamp, 'yvalue' => $avg_left));
}

function getMaxLitres($db) {
	$query_tme = mysqli_query($db, "SELECT `litres`, `range` FROM `profile` ORDER BY `profileid` DESC LIMIT 1");

	while($item = mysqli_fetch_assoc($query_tme)) {

		$maxLitres = floatval($item['litres']);
	}

	return $maxLitres;
}

function getTempHistory($db) {
	
	$query_tme = mysqli_query($db, "SELECT `tstamp`, `tempdc` FROM `temp` ORDER BY `tempid` ASC");
	
	$return_arr = array();
	
	while($item = mysqli_fetch_assoc($query_tme)) {
	
	
		$t = array();
	
		$float_dat = floatval($item['tstamp']);
		$float_val = floatval($item['tempdc']);
	
		$t[] = $float_dat;
		$t[] = $float_val;
	
		$return_arr[] = $t;
	}
	
	return $return_arr;
	
}

function getFlowHistory($db) {

	$query_tme = mysqli_query($db, "SELECT `tstamp`, `ltrspm` FROM `stats` ORDER BY `statsid` ASC");

	$return_arr = array();

	while($item = mysqli_fetch_assoc($query_tme)) {


		$t = array();

		$float_dat = floatval($item['tstamp']);
		$float_val = floatval($item['ltrspm']);

		$t[] = $float_dat;
		$t[] = $float_val;

		$return_arr[] = $t;
	}

	return $return_arr;

}

function getlevlHistory($db) {
	
	$query_profile_max = mysqli_query($db, "SELECT `litres`, `range` FROM `profile` ORDER BY `profileid` DESC LIMIT 1");
	while ($item = mysqli_fetch_assoc($query_profile_max)) {
	
		$max_litres = floatval($item['litres']);
		$max_range = floatval($item['range']);
	}
	
	$query_profile_min = mysqli_query($db, "SELECT `range` FROM `profile` ORDER BY `profileid` ASC LIMIT 1");
	while ($item = mysqli_fetch_assoc($query_profile_max)) {
	
		$min_range = floatval($item['range']);
	}
	
	$query_tme = mysqli_query($db, "SELECT `tstamp`, `currentlevel` FROM `stats` ORDER BY `statsid` ASC");
	$return_arr = array();
	
	while($item = mysqli_fetch_assoc($query_tme)) {
	
		$t = array();
	
		$time_stamp = floatval($item['tstamp']);
		$currentLevel = floatval($item['currentlevel']);
		
		$query_profile_avg = mysqli_query($db, "SELECT AVG(`litres`) AS `avgused` FROM `profile` WHERE `range` = '$currentLevel'");
		while ($item = mysqli_fetch_assoc($query_profile_avg)) {
		
			$avg_used = floatval($item['avgused']);
		}
		
		if($currentLevel >= $min_range AND $currentLevel <= $max_range) {
			$avg_left = $max_litres - $avg_used;
		}
		
		else if($currentLevel < $min_range) {
			$avg_left = $max_litres;
		}
		
		else if ($currentLevel > $max_range) {
			$avg_left = 0.0;
		}
		
		$avg_left = floatval(number_format((float)$avg_left, 2, '.', ''));
	
		$t[] = $time_stamp;
		$t[] = $avg_left;
	
		$return_arr[] = $t;
	}
	
	return $return_arr;
}


function getSettings($db) {
	
	$query_tme = mysqli_query($db, "SELECT `isprofiled`, `fuelppl`, `tsleep` FROM `sysadmin` WHERE `sysadminid` = '1'");
	mysqli_close($db);
	
	while($item = mysqli_fetch_assoc($query_tme)) {
	
		$isProfiled = intval($item['isprofiled']);
		$fuelPrice = floatval($item['fuelppl']);
		$tempRead = floatval($item['tsleep']);
	}
	
	$return_arr = json_encode(array('status' => 'ok', 'profilestatus' => $isProfiled, 'fuelprice' => $fuelPrice, 'tempread' => $tempRead));
	return $return_arr;
	
}
	
function updateFuelSettings($db, $newPrice) {
	
	mysqli_query($db, "UPDATE `sysadmin` SET `fuelppl` = '$newPrice' WHERE `sysadminid` = '1'");
	mysqli_close($db);

}

function updateTempSettings($db, $newInterval) {
	
	mysqli_query($db, "UPDATE `sysadmin` SET `tsleep` = '$newInterval' WHERE `sysadminid` = '1'");
	mysqli_close($db);

}

function updateProfileSettings($db, $newStatus){
	
	mysqli_query($db, "UPDATE `sysadmin` SET `isprofiled` = '$newStatus' WHERE `sysadminid` = '1'");
	mysqli_close($db);
	
}

function getTankStats($db) {
	
	$query_status = mysqli_query($db, "SELECT `isprofiled` FROM `sysadmin` WHERE `sysadminid` = '1'");
	
	while ($item = mysqli_fetch_assoc($query_status)) {
	
		$is_profiled = intval($item['isprofiled']);
	}
	
	
	$query_profile_max = mysqli_query($db, "SELECT `litres`, `range` FROM `profile` ORDER BY `profileid` DESC LIMIT 1");
	while ($item = mysqli_fetch_assoc($query_profile_max)) {
	
		$max_litres = floatval($item['litres']);
		$max_range = floatval($item['range']);
	}
	
	$query_profile_min = mysqli_query($db, "SELECT `range` FROM `profile` ORDER BY `profileid` ASC LIMIT 1");
	while ($item = mysqli_fetch_assoc($query_profile_min)) {
	
		$min_range = floatval($item['range']);
	}
	
	$return_arr = json_encode(array('status' => 'ok', 'profileStatus' => $is_profiled, 'maxRange' => $max_range, 'maxLitres' => $max_litres, 'minRange' => $min_range));
	return $return_arr;
	
}

function makePredictions($db) {
	
	$oneWeekMilli = 604800000;
	$oneMinuteMilli = 60000;
	$oneHourMilli = 3600000;
	
	$currentTimeStamp = (round(microtime(true) * 1000) - $oneHourMilli);
	
	$weekAgoTimeStamp = $currentTimeStamp - $oneWeekMilli;
	
	$query_avg_weekly_cost = mysqli_query($db, "SELECT `tstamp` FROM `stats` WHERE `tstamp` >= '$weekAgoTimeStamp'");
	$weekly_litres = mysqli_num_rows($query_avg_weekly_cost);
	
	$query_get_fuelprice = mysqli_query($db, "SELECT `fuelppl` FROM `sysadmin` WHERE `sysadminid` = '1'");
	while($item = mysqli_fetch_assoc($query_get_fuelprice)) {
		$fuelPrice = floatval($item['fuelppl']);
	}
	
	$weekly_cost = number_format((float)($weekly_litres * $fuelPrice), 2, '.', '');
	
	$query_get_fill_date = mysqli_query($db, "SELECT `tstamp` FROM `stats` WHERE `costpm` = '0' ORDER BY `statsid` DESC LIMIT 1");
	while($item = mysqli_fetch_assoc($query_get_fill_date)) {
		$fillDate = (floatval($item['tstamp']) - $oneHourMilli);
	}
	
	$query_get_last_flow = mysqli_query($db, "SELECT `ltrspm` FROM `stats` ORDER BY `statsid` DESC LIMIT 1");
	while($item = mysqli_fetch_assoc($query_get_last_flow)) {
		$lastestFlowRate = floatval($item['ltrspm']);
	}
	
	$json = getLatestLevl($db);
	$obj = json_decode($json);
	$currentLevel = $obj->{'yvalue'};
	
	$emptyTimeStamp = round($currentTimeStamp + (($currentLevel / $lastestFlowRate) * $oneMinuteMilli));
	
	
	$fillDateString = date("M jS, Y, g:i a", $fillDate / 1000);
	$emptyDateString = date("M jS, Y, g:i a", $emptyTimeStamp / 1000);
	
	$return_arr = json_encode(array('status' => 'ok', 'weeklyCost' => $weekly_cost, 'weeklyUsage' => $weekly_litres, 'tankFillDate' => $fillDateString, 'tankEmptyDate' => $emptyDateString));
	return $return_arr;
	
}


// 	$query_tme = mysqli_query($db, "SELECT `tstamp`, `currentlevel` FROM `stats` ORDER BY `statsid` ASC");

// 	$return_arr = array();

// 	while($item = mysqli_fetch_assoc($query_tme)) {


// 		$t = array();

// 		$float_dat = floatval($item['tstamp']);
// 		$float_val = floatval($item['currentlevel']);

// 		$t[] = $float_dat;
// 		$t[] = $float_val;

// 		$return_arr[] = $t;
// 	}

// 	return $return_arr;



// function populate_date_fields($db) {
	
// 	$options = '';
// 	$query_tme = mysqli_query($db, "SELECT tempid, tstamp FROM temp ORDER BY tempid ASC");
	
// 	while($row = mysqli_fetch_assoc($query_tme)) {
// 		$mili = $row['tstamp'];
// 		$seconds = $mili / 1000;
// 		$options .= "<option>" . date('D-M-y H:i', $seconds) . "</option>";
// 	}
	
// 	$menu="<form id = 'temp_start_date' name = 'start_date' method = 'post' action=''>
//     <select name = 'filter' id = 'filter'>" . $options . "</select>";
	
// 	echo $menu;
// }


?>