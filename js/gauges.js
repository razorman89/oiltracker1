function draw_RT_gauges(maxLitres) {
		
	gaugeRT_levl = new Highcharts.Chart({
		
	    chart: {
	        type: 'gauge',
	        renderTo : 'current_level_container',
	        alignTicks: false,
	        plotBackgroundColor: null,
	        plotBackgroundImage: null,
	        backgroundColor:'transparent',
	        plotBorderWidth: 0,
	        plotShadow: false,
	    },
	    
	    title: {
	        text: 'Current Tank Level'
	    },
	    
	    pane: {
	        startAngle: -180,
	        endAngle: 90,
	    },
	       
	    // the value axis
	    yAxis: {
	        min: 0,
	        max: maxLitres,
	        
	        minorTickInterval: 'auto',
	        minorTickWidth: 1,
	        minorTickLength: 10,
	        minorTickPosition: 'inside',
	        minorTickColor: '#666',
	
	        tickPixelInterval: 30,
	        tickWidth: 2,
	        tickPosition: 'inside',
	        tickLength: 10,
	        tickColor: '#666',
	        labels: {
	            step: 2,
	            rotation: 'auto'
	        },
	        title: {
	            text: 'Litres'
	        },
	        plotBands: [{
	            from: 0,
	            to: (maxLitres/100)*20,
	            color: '#DF5353' // red
	        }, {
	            from: (maxLitres/100)*21,
	            to: (maxLitres/100)*45,
	            color: '#F88017' // orange
	        }, {
	            from: (maxLitres/100)*46,
	            to: (maxLitres/100)*100,
	            color: '#55BF3B' // green
	        }]
	    },
	
	    series: [{
	        name: 'Litres',
	        data: [0],
	        dataLabels: {
	            formatter: function () {
	                var dc = this.y.toFixed(2)
	                return '<span style="color:#339">'+ ' ' + dc + ' L</span><br/>'
	            }
	        },
	        
	        tooltip: {
	        	valueDecimals : 2,
	            valueSuffix: ' L'
	        }
	    }]
	
	})
	
	gaugeRT_temp = new Highcharts.Chart({
		
	    chart: {
	        type: 'gauge',
	        renderTo : 'current_temp_container',
	        alignTicks: false,
	        plotBackgroundColor: null,
	        plotBackgroundImage: null,
	        backgroundColor:'transparent',
	        plotBorderWidth: 0,
	        plotShadow: false,
	    },
	    
	    title: {
	        text: 'Current Temperature'
	    },
	    
	    pane: {
	        startAngle: -170,
	        endAngle: 170,
	    },
	       
	    // the value axis
	    yAxis: {
	        min: -55,
	        max: 125,
	        
	        minorTickInterval: 'auto',
	        minorTickWidth: 1,
	        minorTickLength: 10,
	        minorTickPosition: 'inside',
	        minorTickColor: '#666',
	
	        tickPixelInterval: 30,
	        tickWidth: 2,
	        tickPosition: 'inside',
	        tickLength: 10,
	        tickColor: '#666',
	        labels: {
	            step: 2,
	            rotation: 'auto'
	        },
	        title: {
	            text: 'Celsius'
	        },
	        plotBands: [{
	            from: -55,
	            to: 0,
	            color: '#3BB9FF' // blue
	        }, {
	            from: 1,
	            to: 35,
	            color: '#55BF3B' // green
	        }, {
	            from: 36,
	            to: 65,
	            color: '#F88017' // orange
	        }, {
	            from: 66,
	            to: 125,
	            color: '#DF5353' // red
	        }]
	    },
	
	    series: [{
	        name: 'Temperature',
	        data: [0],
	        dataLabels: {
	            formatter: function () {
	                var dc = this.y.toFixed(2),
	                    df = ((9/5) * dc + 32).toFixed(2);
	                return '<span style="color:#339">'+ ' ' + dc + ' °C</span><br/>' +
	                    '<span style="color:#933">' + df + ' °F</span>';
	            }
	        },
	        
	        tooltip: {
	        	valueDecimals : 2,
	            valueSuffix: ' °C'
	        }
	    }]
	
	})
}
