<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Derek O'Connor Project 2013</title>

	<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
	<script src="http://code.jquery.com/jquery-migrate-1.1.1.min.js"></script>
	<script src="http://code.highcharts.com/highcharts.js"></script>

	<style type="text/css">
		div#container { width: 550px; }
	</style>
	
	<script type="text/javascript">
	var chart1;
	
	jQuery(document).ready(function() 
		{

		    chart1 = new Highcharts.Chart({
			    
		        chart: {
		            renderTo: 'temp_container',
		            type: 'spline',
			        borderWidth: 2,
			        spacingRight: 20
		        },
		        		        
		        title: {
			        
		            text: 'Temperature Readings'
			            
		        },
		        
		        xAxis: {
			        
			        
		            type: 'datetime',

		            title: {
		                text: 'Date / Time'
		            }
	            
		            /**
		            labels: {
		                formatter: function() {
		                    var monthStr = Highcharts.dateFormat('%b', this.value);
		                    var firstLetter = monthStr.substring(0, 1);
		                    return firstLetter;
		                }
	            	}
				*WORKING HERE LAST FORMATTING THE LABELS*/ 	     
					       
		        },
		        
		        yAxis: {
			        
		            title: {
		                text: 'Temperature Degrees C'
		            }
	            
		        },
		        
		        series: [{

	                data: []
	            }]
		        
		    });

		    update_chart();

		    function update_chart(){
		    	console.log("attempt to update chart");
		    	
			    $.post('php/get_data.php', { data: 'temp' }, function (data) {
				    if(data.status == 'ok'){
				    	console.log("data is ok!");
				    	
					    // update my chart
						chart1.series[0].setData(data.series_data);
						console.log("Got new data.. yah!");

				    }
				    else{
					    
					    alert('ERROR UPDATEING CHART');
				    }
				}, "json");
				
				setTimeout(update_chart, 10000);
		    }		    
		
		});
	</script>
</head>
<body>
	<h1>HUZZA IT WORKS!</h1>
	<div id="temp_container">
		
	</div>

</body>
</html>