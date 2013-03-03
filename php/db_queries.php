<?php

function getDist(){
	
	$query = mysqli_query($db, "SELECT * FROM temp");
	return $query->result();
	
}

function getFlow(){
	
	$query = mysqli_query($db,"SELECT * FROM temp");
	return $query->result();
}

function getTemp($db){
	$query_tme = mysqli_query($db, "SELECT tstamp, tempdc FROM project2013.temp;");
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

?>