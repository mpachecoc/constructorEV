(function($)
{
	if (typeof charts == 'undefined') 
		return;

	charts.chart_simple = 
	{
		// data
		data: 
		{
			d1: [],
			d2: []
		},
		
		// will hold the chart object
		plot: null,

		// chart options
		options: 
		{
			grid: 
			{
			    color: "#dedede",
			    borderWidth: 1,
			    borderColor: "transparent",
			    clickable: true, 
			    hoverable: true
			},
	        series: {
	            lines: {
            		show: true,
            		fill: false,
            		lineWidth: 2,
            		steps: false
            	},
	            points: {
	            	show:true,
	            	radius: 4,
	            	lineWidth: 3,
	            	fill: true,
	            	fillColor: "#000"
	            }
	        },
	        xaxis: {
				tickColor: 'transparent',
				tickDecimals: 0,
				tickSize: 1
			},
			xaxes: [{ 
				position: 'bottom', axisLabel: 'X Axis', showTickLabels: 'none' 
			}],
			yaxis: {
				tickSize: 500
			},
	        legend: { position: "nw", noColumns: 2, backgroundColor: null, backgroundOpacity: 0 },
	        shadowSize: 0,
	        tooltip: true,
			tooltipOpts: {
				content: "%s : %y.3",
				shifts: {
					x: -30,
					y: -50
				},
				defaultTheme: false
			}
		},
		
		placeholder: "#chart_simple",

		// initialize
		init: function()
		{
			// this.options.colors = ["#72af46", "#466baf"];
			this.options.colors = [successColor, primaryColor];
			this.options.grid.backgroundColor = { colors: ["#fff", "#fff"]};

			var that = this;

			if (this.plot == null)
			{
			 	this.data.d1 = [ [1, 1], [2, 400], [3, 1000], [4, 1500], [5, 2000], [6, 2250], [7, 2500], [8, 2520], [9, 2700], [10, 2570], [11, 2700], [12, 3300] ];
			 	this.data.d2 = [ [1, 1], [2, 200], [3, 600], [4, 1200], [5, 2200], [6, 2500], [7, 2600], [8, 2360], [9, 2200], [10, 2460], [11, 3000], [12, 3400] ];
			}
			this.plot = $.plot(
				$(this.placeholder),
	           	[{
	    			label: "Curva Prevista", 
	    			data: this.data.d1,
	    			lines: { fill: 0.05 },
	    			points: { fillColor: "#fff" }
	    		}, 
	    		{	
	    			label: "Curva Actual",
	    			data: this.data.d2,
	    			lines: { fill: 0.1 },
	    			points: { fillColor: that.options.colors[1] }
	    		}], this.options);
		}
	};
	
	// uncomment to init on load
	charts.chart_simple.init();

	// use with tabs
	$('a[href="#chart-simple-lines"]').on('shown.bs.tab', function(){
		if (charts.chart_simple.plot == null)
			charts.chart_simple.init();
	});

	$('.btn-group [data-toggle="tab"]').on('show.bs.tab', function(){
		$(this).parent().find('[data-toggle]').removeClass('active');
		$(this).addClass('active');
	});

})(jQuery);