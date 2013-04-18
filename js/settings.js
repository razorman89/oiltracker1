function populateFields() {

	var profileStatus = 0;
	var fuelPrice = 0;
	var tempReadTime = 0;

	$.post('php/get_data.php/', {
		dataType : "read_settings",
		actionType : "settings_read"
		
	}, function(data) {
		
		if (data.status == 'ok') {
			console.log(" ** DATA STATUS: ok ** ");
			profileStatus = data.profilestatus;
			fuelPrice = data.fuelprice;
			tempReadTime = data.tempread;
			
			console.log(" ** system 'isProfiled' equals:" + profileStatus + 
						" **\n ** system 'fuelPrice' equals:" + fuelPrice + 
						" **\n ** system 'tempReadTime' equals:" + tempReadTime + 
						" **");
		}
		
		else {
			console.log(" ** data status: corrupt / not set ** ");
		}
		
	}, "json").done(

	function(){

		if(profileStatus == "1") {
			console.log(" ** SETTING TRUE ** ");
			$('#profile_toggle').prop('checked', false);
			
		}
		
		else if(profileStatus == "0") {
			console.log(" ** SETTING FALSE ** ");
			$('#profile_toggle').prop('checked', true);
			
		}

		$('#currentPrice').val(" €" + fuelPrice);
		$('#currentReadTime').val(" " + tempReadTime + " seconds");

		console.log(" ** FINISHED DONE FUNCTION ** ");

	});
	
}

function toggleProfile() {
	$('#profile_toggle').change(function() {

		var isChecked = 1;
		if($('#profile_toggle').is(':checked')) {
			isChecked = 0;
		}

		$.post('php/get_data.php/', {
			newData: isChecked,
			dataType : "profile_status",
			actionType : "settings_write"
			
		}, function() {
			
			populateFields();
			updateTankStats();
			
		});
	});
}

function updateFuel() {
	$('#fuel_settings').submit(function() { /* AJAX Code */ 
		var result;

		$.post('php/get_data.php/', {
			newData: $('#newPrice').val(),
			dataType : "fuel_price",
			actionType : "settings_write"
			
		}, function() {
			
			populateFields();
			$('#newPrice').val("");
			
		});
		return false;
	});
}

function updateInterval() {
	$('#temp_settings').submit(function() { /* AJAX Code */ 
		var result;

		$.post('php/get_data.php/', {
			newData: $('#newReadTime').val(),
			dataType : "temp_read_interval",
			actionType : "settings_write"
			
		}, function() {
			
			populateFields();
			$('#newReadTime').val("");
			
		});
		return false;
	});
}

function updateMaxLitres() {
	var maxLitres;

	$.post('php/get_data.php/', {
		dataType : "profiled_litres",
		actionType : "settings_read"
		
	}, function(data) {
		
		if (data.status == 'ok') {
			console.log(" ** DATA STATUS: ok ** ");
			maxLitres = data.maxLitres;	
		}
		
		else {
			console.log(" ** data status: corrupt / not set ** ");
		}
		
	}, "json").done(

	function(){

		if (maxLitres == null) {
			maxLitres = 0.0;
		}

		$('#litresProfiled').val(" " + maxLitres + " L");
		
		console.log(" ** FINISHED DONE FUNCTION ** ");

	});
	
}

function updateTankStats() {
	var status;
	var tankCapacity;
	var rangeWhenFull;
	var rangeWhenEmpty; 
	
	$.post('php/get_data.php/', {
		dataType : "tank_stats",
		actionType : "settings_read"
		
	}, function(data) {
		
		if (data.status == 'ok') {
			console.log(" ** DATA STATUS: ok ** ");
			status = data.profileStatus	
			tankCapacity = data.maxLitres;
			rangeWhenFull = data.minRange;
			rangeWhenEmpty = data.maxRange;
		}
		
		else {
			console.log(" ** data status: corrupt / not set ** ");
		}
		
	}, "json").done(

	function(){

		if (status == "0") {
			$('#profileStatus').val(" ON");
		}

		else if (status == "1") {
			$('#profileStatus').val(" OFF");
		}

		if (tankCapacity == null) {
			tankCapacity = 0.0;
		}
		$('#tankCapacity').val(" " + tankCapacity + " L");

		if (rangeWhenFull == null) {
			rangeWhenFull = 0;
		}
		$('#rangeFull').val(" " + rangeWhenFull + " CM");

		if (rangeWhenEmpty == null) {
			rangeWhenEmpty = 0;
		}
		$('#rangeEmpty').val(" " + rangeWhenEmpty + " CM");
		
		console.log(" ** FINISHED DONE FUNCTION ** ");

	});
	
}