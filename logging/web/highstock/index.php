<?php
require_once('../5004/includes.php');
?>
<html>

<body>
<div class="container">
	<div id="container" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
        </div> <!--end container-->
</div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/highstock.js"></script>
	<script src="js/exporting.js"></script>
        <script type="text/javascript">

$(function() {

	Highcharts.setOptions({
		lang: {
			months: ['január', 'február', 'március', 'április', 'május', 'június',  'július', 'augusztus', 'szeptember', 'október', 'november', 'december'],
			weekdays: ['vasárnap', 'hétfő', 'kedd', 'szerda', 'csütörtök', 'péntek', 'szombat']
		}
	});


	var seriesOptions = [],
		yAxisOptions = [],
		seriesCounter = 0,
		names = [
<?php
#require_once('../5004/constants.php');

foreach($switch_devices as $device)
{
  $device_name=substr($device, strrpos($device, "/")+1);
  if ( substr($device_name,0,2) != "28" ) {
    continue;
  }
  $settings_path=$sensors_settings_path."/".$device_name;
  $device_alias = read_file($settings_path."/alias");
  $device_alias = substr($device_alias,0,-1);
  echo "			{name: '$device_alias', id: '$device_name' },\n";
}
?>
//                        {name: 'device_alias', id: '28.C0108F040000' }

		],
		colors = Highcharts.getOptions().colors;

	$.each(names, function(i, location) {

//		$.getJSON('get_rrd.php?host=logging&sensorid=' + location.id,	function(data) {
//                $.getJSON('getJSON.php?host=logging&sensorid=' + location.id,   function(data) {

//                $.getJSON('getJSONsqlite.php?sensorid=' + location.id,   function(data) {
                $.getJSON('data.php?sensorid=' + location.id,   function(data) {


                var start = new Date().getTime();
                var minute = 60*1000;
                start = start - (1*60*minute);



			seriesOptions[i] = {
			        name: location.name,
			        data: data,
//			        pointStart: Date.UTC(2014, 02, 26, 16, 00),
                                pointStart: start,
			        pointInterval: minute,
			        tooltip: {
			        	valueDecimals: 3,
			        	valueSuffix: '°C',
				        dateTimeLabelFormats: {
						    millisecond:"%A, %b %e, %H:%M:%S.%L",
						    second:"%A, %b %e, %H:%M:%S",
						    minute:"%A, %b %e, %H:%M",
						    hour:"%Y. %b %e, %A, %H:%M",
						    day:"%Y. %b %e, %A",
						    week:"Week from %A, %b %e, %Y",
						    month:"%B %Y",
						    year:"%Y"
						}
			        }
			};

			// As we're loading the data asynchronously, we don't know what order it will arrive. So
			// we keep a counter and create the chart when all the data is loaded.
			seriesCounter++;

			if (seriesCounter == names.length) {
				createChart();
			}
		});
	});



	// create the chart when all data is loaded
	function createChart() {

		$('#container').highcharts('StockChart', {
		    chart: {
		    	height: 500,
		        renderTo: 'container',
				events: {
					load: function(chart) {
						this.setTitle(null, {
							text: 'A táblázat '+ (new Date() - start) +'ms alatt készült.'
						});
					}
				},
				zoomType: 'x'
		    },
			exporting: {
				enabled: false	
			},
                    tooltip: {
		//	formatter: function() {
		  //              return Highcharts.dateFormat('%l%p', this.x-(1000*3600)) +'-'+ Highcharts.dateFormat('%l%p', this.x) +': <b>'+ this.y + '</b>';
		//	}
		    },
		    legend: {
		    	enabled: true,
		    	align: 'center',
		    	verticalAlign: 'bottom',
		    },			
			rangeSelector: {
		        buttons: [{
		            type: 'hour',
		            count: 1,
		            text: '1ó'
		        },{
		            type: 'hour',
		            count: 24,
		            text: '1n'
		        },{
		            type: 'day',
		            count: 3,
		            text: '3n'
		        }, {
		            type: 'week',
		            count: 1,
		            text: '1hé'
		        }, {
		            type: 'month',
		            count: 1,
		            text: '1hó'
		        }, {
		            type: 'month',
		            count: 6,
		            text: '6hó'
		        }, {
		            type: 'year',
		            count: 1,
		            text: '1é'
		        }, {
		            type: 'all',
		            text: 'mind'
		        }],
		        selected: 5
			},

		    yAxis: {
				title: {
					text: 'Hőmérséklet (°C)'
				}
		    },
		    
		    title: {
				text: 'Hőmérsékleti adatok'
			},
	
			
		    series: seriesOptions
		});
	}

});

	</script>


  </body>

</html>
