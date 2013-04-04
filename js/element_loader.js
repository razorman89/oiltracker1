

function load_list(type, action) {
	
	console.log(" ** loading page elements ** ");
	
	$.post('php/get_elements.php/', { dataType: type, actionType: action }, function (data) {
		
	    		
	    document.getElementById('temp_start_date').innerHTML = data;
	    		
		
	});
	
}