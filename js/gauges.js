function draw_RT_gauges2() {
		
	gaugeRT_temp2 = new Highcharts.Chart({
		
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
	        background: [{
	            backgroundColor: {
	                linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
	                stops: [
	                    [0, '#FFF'],
	                    [1, '#333']
	                ]
	            },
	            borderWidth: 0,
	            outerRadius: '109%'
	        }, {
	            backgroundColor: {
	                linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
	                stops: [
	                    [0, '#333'],
	                    [1, '#FFF']
	                ]
	            },
	            borderWidth: 1,
	            outerRadius: '107%'
	        }, {
	            // default background
	        }, {
	            backgroundColor: '#DDD',
	            borderWidth: 0,
	            outerRadius: '105%',
	            innerRadius: '103%'
	        }]
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
	            text: '°C'
	        },
	        plotBands: [{
	            from: -55,
	            to: 0,
	            color: '#3BB9FF' // blue
	        }, {
	            from: 0,
	            to: 35,
	            color: '#55BF3B' // green
	        }, {
	            from: 35,
	            to: 65,
	            color: '#F88017' // orange
	        }, {
	            from: 65,
	            to: 125,
	            color: '#DF5353' // red
	        }]
	    },
	
	    series: [{
	        name: 'Temperature',
	        data: [80],
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


function update_gauge(chart) {
	
    setInterval(function() {
        var point = chart.series[0].points[0],
            newVal, inc = Math.round((Math.random() - 0.5) * 20);

        newVal = point.y + inc;
        if (newVal < -55 || newVal > 125) {
            newVal = point.y - inc;
        }

        point.update(newVal);

    });

}
