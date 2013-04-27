function make_ST_predictions() {
	
	$.post('php/get_data.php/', {
		dataType : "predictions",
		actionType : "make_predictions",
		
	}, function(data) {
		
		if (data.status == 'ok') {
			console.log(" ** DATA STATUS: ok ** ");
			cost = data.weeklyCost	
			usage = data.weeklyUsage;
			fillDate = data.tankFillDate;
			emptyDate = data.tankEmptyDate;
		}
		
		else {
			console.log(" ** data status: corrupt / not set ** ");
		}
		
	}, "json").done(

	function() {

		$('#avgCost').val(" € " + cost + "");
		$('#avgUsage').val(" " + usage + " Litres");
		$('#tankFilledDate').val(" " + fillDate + " ");
		$('#tankemptyDate').val(" " + emptyDate + " ");
		console.log(" ** PREDICTIONS MADE ** ");

	});	
}