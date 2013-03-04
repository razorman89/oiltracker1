var chart_temp;
var chart_flow;
var chart_dist;
var highchartsOptions = Highcharts.setOptions(Highcharts.theme);
var temp_max_index, flow_max_index, dist_max_index;

function drawRealCharts() {
	
	console.log(" ** creating chart object ** ");
	
	chart_temp = new Highcharts.Chart({
	    
        chart: {
            renderTo: 'temp_container',
            type: 'spline',
	        borderWidth: 2,
	        events: {
	        	load: function() {
	        		chart_temp = this; // `this` is the reference to the chart
	        		init_chart("temp", chart_temp, "init");
	        	}
	        }

        },
        
        plotOptions: {
            line: {
                animation: true
            }
        },
        		        
        title: {
	        
            text: 'Temperature Readings'
	            
        },
        
        xAxis: {
	        
	        
            type: 'datetime',

            title: {
                text: 'Date / Time'
            }
			       
        },
        
        yAxis: {
	        
            title: {
                text: 'Temperature Degrees C'
            }
        
        },
        
        series: [{
        	
        	name: 'External Temperature',
            data: []

        }]
        
    });
	
	/***********************************************************************************************/
	
	chart_flow = new Highcharts.Chart({
	    
        chart: {
            renderTo: 'flow_container',
            type: 'spline',
	        borderWidth: 2,
	        events: {
	        	load: function() {
	        		chart_flow = this; // `this` is the reference to the chart
	        		init_chart("flow", chart_flow, "init");
	        	}
	        }
        },
        
        plotOptions: {
            line: {
                animation: true
            }
        },
        		        
        title: {
	        
            text: 'Flow Readings'
	            
        },
        
        xAxis: {
	        
	        
            type: 'datetime',
            tickPixelInterval: 150,

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
                text: 'Flow in Liters'
            }
        
        },
        
        series: [{

            data: []
        }]
        
    });
	
	/***********************************************************************************************/
	
	chart_levl = new Highcharts.Chart({
	    
        chart: {
            renderTo: 'dist_container',
            type: 'column',
	        borderWidth: 2,
	        events: {
	        	load: function() {
	        		chart_levl = this; // `this` is the reference to the chart
	        		init_chart("levl", chart_levl, "init");
	        	}
	        }
        },
        
        plotOptions: {
            line: {
                animation: true
            }
        },
        		        
        title: {
	        
            text: 'Tank Levels'
	            
        },
        
        xAxis: {
	        
	        
            type: 'datetime',
            tickPixelInterval: 150,

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
                text: 'Range to Oil in CM'
            }
        
        },
        
        series: [{

            data: []
        }]
        
    });

}


function update_chart(type, chart, action){
	
	console.log(" ** requesting to update chart data ** ");
	$.post('php/get_data.php/', { dataType: type, actionType: action }, function (data) {
		
	    if(data.status == 'ok') {	
	    	
	    	console.log(" ** data status: ok ** ");
	    	var pointChart = new Array(data.xvalue, data.yvalue);

	    	if(type == "temp") {
	    		
		    	if(data.index != temp_max_index) {
		    		
		    		chart.series[0].addPoint(pointChart, true, true);
		    		console.log(" ** temp chart updated ** ");
		    		temp_max_index = data.index;
		    		
		    	}
		    	
		    	else{
		    		
		    		console.log(" ** no new temp data ** ");
		    		
		    	}
	    		
	    	}
	    	
	    	else if(type == "flow") {
	    		
		    	if(data.index != flow_max_index) {
		    		
		    		chart.series[0].addPoint(pointChart, true, true);
		    		console.log(" ** flow chart updated ** ");
		    		flow_max_index = data.index;
		    		
		    	}
		    	
		    	else{
		    		
		    		console.log(" ** no new flow data ** ");
		    		
		    	}
	    		
	    	}
	    	
	    	else if(type == "levl") {
	    		
		    	if(data.index != dist_max_index) {
		    		
		    		chart.series[0].addPoint(pointChart, true, true);
		    		console.log(" ** level chart updated ** ");
		    		dist_max_index = data.index;
		    		
		    	}
		    	
		    	else{
		    		
		    		console.log(" ** no new level data ** ");
		    		
		    	}
	    		
	    	}
	    	
	    	else {
	    		
	    		console.log(" ** error selecting 'type' ** ");
	    		
	    	}		

	    }
	    
	    else{
		    
	    	console.log(" ** data status: corrupt / not set ** ");
	    	
	    }
	    
	}, "json");
	
}

function init_chart(type, chart, action){
	
	console.log(" ** requesting initial chart data ** ");
	
    $.post('php/get_data.php/', { dataType: type, actionType: action }, function (data) {
    	
	    if(data.status == 'ok'){
	    	
	    	console.log(" ** data status: ok ** ");
			chart.series[0].setData(data.series_data);
			console.log(" ** chart initialised ** ");

	    }
	    
	    else{
		    
	    	console.log(" ** data status: corrupt / not set ** ");
	    	
	    }
	    
	}, "json");
}