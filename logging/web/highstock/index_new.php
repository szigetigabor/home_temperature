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
  echo "                        {name: '$device_alias', id: '$device_name' },\n";
}
?>
//                        {name: 'device_alias', id: '28.C0108F040000', pointStart: start }

		],
		colors = Highcharts.getOptions().colors;

	$.each(names, function(i, location) {

                $.getJSON('data_new2.php?sensorid=' + location.id,   function(data) {

			seriesOptions[i] = {
			        name: location.name,
			        data: data,
			        tooltip: {
			        	valueDecimals: 3,
			        	valueSuffix: '°C',
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

		$('#container').highcharts('StockChart',{
		    chart: {
		    	height: 500,
		        renderTo: 'container',
				zoomType: 'x',
				type: 'spline'
		    },
		    legend: {
		    	enabled: true,
		    	align: 'center',
		    	verticalAlign: 'bottom',
		    },			
                    xAxis: {
                        type: 'datetime',           
		    },
		    yAxis: {
			title: {
				text: 'Hőmérséklet (°C)'
			}
		    },
		    
		    title: {
			text: 'Hőmérsékleti adatok'
			},
			plotOptions: {
                             line: {
                                  marker: {
                                          enabled: false
                                  }
                             },
                             series: {
   		                  connectNulls: false,
                             }
                        },	
			
		    series: seriesOptions
		});
	}

});

	</script>


  </body>
</html>

