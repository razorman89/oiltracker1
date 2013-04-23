// Highcharts loading style for realtime charts, preventing code bloat as it is reused several times
var loading_style_RT = {
	hideDuration : 2500,
	showDuration : 1500,
	labelStyle : {
		fontWeight : 'bold',
		position : 'relative',
		top : '45%',
		color : 'white'
	},
	style : {
		position : 'absolute',
		backgroundColor : 'black',
		opacity : 0.4,
		textAlign : 'center'
	}
};

// Highcharts loading style for history stockcharts, preventing code bloat as it is reused several times
var loading_style_HS = {
	hideDuration : 2500,
	showDuration : 500,
	labelStyle : {
		fontWeight : 'bold',
		position : 'relative',
		top : '45%',
		color : 'white'
	},
	style : {
		position : 'absolute',
		backgroundColor : 'black',
		opacity : 0.8,
		textAlign : 'center'
	}
};

var highchartsOptions = Highcharts.setOptions(Highcharts.theme);
var temp_max_index, flow_max_index, dist_max_index;

Highcharts.setOptions({
	global: {
		useUTC: false
	}
});

/**
 * Creates & initialises three realtime HighChart.Chart objects
 * @author Derek O'Connor
 * @param none
 * @this Highcharts.Chart
 * @see	#init_RT_chart()
 * @return Highcharts.Chart, (chartRT_temp, chartRT_flow, chartRT_levl)
 */
function draw_RT_charts(maxLitres) {

	console.log(" ** creating real time chart elements ** ");
	/*
	 * create realtime temperature chart
	 */
	chartRT_temp = new Highcharts.Chart({
		// chart options
		chart : {
			renderTo : 'temp_container',
			type : 'spline',
			borderWidth : 2,
			zoomType: 'xy',
			events : {
				load : function() {
					chartRT_temp = this; // `this` is the reference to the chart
					this.showLoading();  //  display loading overlay
					init_RT_chart("temp", chartRT_temp, "init"); // load last ten values to init the chart
				}
			}
		},
		
		loading : loading_style_RT, // loading options defined above

		plotOptions : {
			line : {
				animation : true
			}
		},

		title : {

			text : 'Temperature Readings'

		},

		xAxis : {

			type : 'datetime',
			tickPixelInterval : 150,

			title : {
				text : 'Date / Time'
			}

		},

		yAxis : {

			title : {
				text : 'Temperature Degrees °C'
			}

		},

		tooltip : {
			followPointer : true

		},

		series : [ {

			name : 'External Temperature',
			data : [],
	        tooltip: {
	        	valueDecimals : 2,
	            valueSuffix: ' °C'
	        }

		} ]

	});

	/***********************************************************************************************/
	/*
	 * create realtime flow chart
	 */
	chartRT_flow = new Highcharts.Chart({

		chart : {
			renderTo : 'flow_container',
			type : 'spline',
			borderWidth : 2,
			zoomType: 'xy',
			events : {
				load : function() {
					chartRT_flow = this; // `this` is the reference to the chart
					this.showLoading();  //  display loading overlay
					init_RT_chart("flow", chartRT_flow, "init"); // load last ten values to init the chart
				}
			}
		},

		loading : loading_style_RT, // loading options defined above

		plotOptions : {
			line : {
				animation : true
			}
		},

		title : {

			text : 'Oil Flow'

		},

		xAxis : {
			type : 'datetime',
			tickPixelInterval : 150,

			title : {
				text : 'Date / Time'
			}
		},

		yAxis : {

			title : {
				text : ''
			}

		},

		tooltip : {
			followPointer : true

		},

		series : [ {
			name : 'Liters per Minute',
			data : [],
	        tooltip: {
	        	valueDecimals : 2,
	            valueSuffix: ' L/min'
	        }
		
		}, {
			name : 'Cost per Minute',
			data : [],
	        tooltip: {
	        	valueDecimals : 2,
	            valuePrefix: '€ '
	        }
		}]

	});

	/***********************************************************************************************/
	/*
	 * create realtime level chart
	 */
	chartRT_levl = new Highcharts.Chart({

		chart : {
			renderTo : 'levl_container',
			type : 'bar',
			borderWidth : 2,
			spacingRight : 30,
			events : {
				load : function() {
					chartRT_levl = this; // `this` is the reference to the chart
					this.showLoading();  //  display loading overlay
					//init_RT_chart("levl", chartRT_levl, "init"); // load last ten values to init the chart
				}
			}
		},

		loading : loading_style_RT, // loading options defined above

		plotOptions : {
			line : {
				animation : true
			}
		},

		title : {

			text : 'Tank Level'

		},

		xAxis : {

			labels : {
				rotation : -90,
				align : 'right',
				style : {
					fontSize : '.001px',
				}
			},
			type : 'datetime',
			tickPixelInterval : 150,

		},

		yAxis : {
			min : 0,
			max : maxLitres,
			title : {
				text : 'Approximate Litres in Tank'
			}

		},

		tooltip : {
			followPointer : true

		},

		series : [ {		
			name : 'Litres: ',
			data : [],
	        tooltip: {
	        	valueDecimals : 2,
	            valueSuffix: ' L'
	        },		

		} ]

	});

}

/**
 * Creates three history HighChart.StockChart objects
 * @author Derek O'Connor
 * @param type The type of data temp_history, flow_history, levl_history
 * @param data The data for graphing JSON encoded
 * @this Highcharts.StockChart
 * @return none
 */
function draw_HS_chart(type, data) {

	if (type == 'temp_history') {

		chartHS_temp = new Highcharts.StockChart({

			chart : {
				renderTo : 'temp_container',
				type : 'areaspline',
				events : {
					load : function() {
						chartHS_temp = this; // 'this' is the reference to the chart
						this.showLoading();
					}
				}
			},

			loading : loading_style_HS,

			rangeSelector : {
				selected : 1
			},

			title : {
				text : 'Temperature History'
			},

			series : [ {
				name : 'Temp',
				data : data,
		        tooltip: {
		        	valueDecimals : 2,
		            valueSuffix: ' °C'
		        }
			} ]
		});
		setTimeout(function() {
			chartHS_temp.hideLoading();
		}, 500);
	}

	else if (type == 'flow_history') {

		chartHS_flow = new Highcharts.StockChart({

			chart : {
				renderTo : 'flow_container',
				type : 'areaspline',
				events : {
					load : function() {
						chartHS_flow = this; // 'this' is the reference to the chart
						this.showLoading();
					}
				}
			},

			loading : loading_style_HS,

			rangeSelector : {
				selected : 1
			},

			title : {
				text : 'Oil Flow History'
			},

			series : [ {
				name : 'Liters per Minute',
				data : data,
		        tooltip: {
		        	valueDecimals : 2,
		            valueSuffix: ' L/min'
		        }
			} ]
		});
		setTimeout(function() {
			chartHS_flow.hideLoading();
		}, 500);
	}

	else if (type == 'levl_history') {

		chartHS_levl = new Highcharts.StockChart({

			chart : {
				renderTo : 'levl_container',
				type : 'areaspline',
				events : {
					load : function() {
						chartHS_levl = this; // 'this' is the reference to the chart
						this.showLoading();
					}
				}
			},

			loading : loading_style_HS,

			rangeSelector : {
				selected : 1
			},

			title : {
				text : 'Tank Level History'
			},

			series : [ {
				name : 'Litres in Tank',
				data : data,
		        tooltip: {
		        	valueDecimals : 2,
		            valueSuffix: ' L'
		        }
			} ]
		});
		
		setTimeout(function() {
			chartHS_levl.hideLoading();
		}, 500);
	}
}

/**
 * Upadates realtime charts via ajaxiation, only pushes new data to series
 * @author Derek O'Connor
 * @param type The chart type to update temp, flow, levl
 * @param chart A referance to the chart object allowing new data points to be pushed onto the series
 * @param action Selector used in 'get_data.php'
 * @see php/get_data.php/
 * @return Json encoded string with single key, value pair of latest databse value
 */
function update_RT_chart(chart, dataType, action, chartType) {

	console.log(" ** requesting to update chart data ** ");
	$.post('php/get_data.php/', {
		dataType : dataType,
		actionType : action
	}, function(data) {

		if (data.status == 'ok') {

			console.log(" ** data status: ok ** ");
			var pointChart1 = new Array(data.xvalue, data.yvalue);
			var series = chart.series[0];
			var point = chart.series[0].points[0];
			var shiftTemp = series.data.length >= 10; // shift after 10 values are loaded
			var shiftFlow = series.data.length > 7; // shift after 7 values are loaded
			var shiftLevl = series.data.length > 0; // shift after one value is loaded

			if (chartType == "temp_chart") {

				if (data.index != temp_max_index) {

					chart.series[0].addPoint(pointChart1, true, shiftTemp);
					console.log(" ** temp chart updated ** ");
					temp_max_index = data.index; // keep track of the max index of the database
				}

				else {
					console.log(" ** no new temp data ** ");
				}
			}

			else if (chartType == "flow_chart") {

				if (data.index != flow_max_index) {
					
					var pointChart2 = new Array(data.xvalue, data.costvalue);
					chart.series[0].addPoint(pointChart1, true, shiftFlow);
					chart.series[1].addPoint(pointChart2, true, shiftFlow);
					console.log(" ** flow chart updated ** ");
					flow_max_index = data.index; // keep track of the max index of the database
				}

				else {
					console.log(" ** no new flow data ** ");
				}
			}

			else if (chartType == "levl_chart") {
				
				if (data.yvalue <= 2) {
					console.log(" ** level chart color = red ** ");
					series.color = '#FB0101';
				}
					
				if (data.yvalue > 2 && data.yvalue <= 5) {
					console.log(" ** level chart color = amber ** ");
					series.color = '#F88017';
				}
					
				if (data.yvalue > 5) {
					console.log(" ** level chart color = green ** ");
					series.color = '#55BF3B';
				}

				chart.series[0].addPoint(pointChart1, true, shiftLevl);					
				console.log(" ** level chart updated ** ");
				chart.hideLoading();
			
			}
			
			else if (chartType == "temp_gauge") {
				
				point.update(data.yvalue);
				console.log(" ** current temp gauge updated ** ");
				chart.hideLoading();
			
			}

			else {
				console.log(" ** error selecting 'type' ** ");
			}
		}

		else {
			console.log(" ** data status: corrupt / not set ** ");
		}

	}, "json");

}

/**
 * Initialise realtime charts via ajaxiation,
 * @author Derek O'Connor
 * @param type The chart type to update temp, flow, levl
 * @param chart A referance to the chart object allowing series[0] to be set
 * @param action Selector used in 'get_data.php'
 * @see php/get_data.php/
 * @return Json encoded string containing either 10 or 1 data points
 */
function init_RT_chart(type, chart, action) {

	console.log(" ** requesting initial chart data ** ");

	$.post('php/get_data.php/', {
		dataType : type,
		actionType : action
	}, function(data) {

		if (data.status == 'ok') {

			console.log(" ** data status: ok ** ");
			if(type == 'flow'){
				chart.series[0].setData(data.series_ltrs_data);
				chart.series[1].setData(data.series_cost_data);
			}
			
			else if(type == 'levl'){
				chart.series[0].setData(data.series_data);
			}
			
			else {
				chart.series[0].setData(data.series_data);
			}
			
			console.log(" ** " + type +" chart initialised ** ");
			chart.hideLoading();

		}

		else {

			console.log(" ** data status: corrupt / not set ** ");

		}

	}, "json");
}

/**
 * Load get data for history chart via ajaxiation, then passes this data to 'draw_HS_chart' function
 * @param type The chart type to update temp, flow, levl
 * @param action Selector used in 'get_data.php'
 * @see php/get_data.php/
 * @see #draw_HS_chart()
 * @return Json encoded string containing all data in SQL table
 */
function load_HS_chart(type, action) {

	console.log(" ** requesting chart history ** ");

	$.post('php/get_data.php/', {
		dataType : type,
		actionType : action
	}, function(data) {

		if (data.status == 'ok') {

			console.log(" ** data status: ok ** ");
			draw_HS_chart(data.type, data.history_data);
			console.log(" ** chart initialised, '" + data.type + "' data loaded ** ");
		}

		else {

			console.log(" ** data status: corrupt / not set ** ");

		}

	}, "json");
}


function init_graph_vars(type, action){
	
	$.post('php/get_data.php/', {
		dataType : type,
		actionType : action
		
	}, function(data) {
		
		if (data.status == 'ok') {
			console.log(" ** DATA STATUS: ok ** ");
			maxLitres = data.maxLitres;
			console.log(" ** system 'MaxLitres' equals:" + maxLitres + " ** ");
		}
		
		else {
			console.log(" ** data status: corrupt / not set ** ");
		}
		
		return maxLitres;
		
	}, "json");

}