<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> 
 
<title>Using Highcharts with PHP and MySQL</title>

<script type="text/javascript" src="js/jquery-1.7.1.min.js" ></script>
<script type="text/javascript" src="js/highstock.js" ></script>
<script type="text/javascript" src="js/themes/grid.js"></script>

<script type="text/javascript">
	var chart;
<?php
if (isset($_GET['sensorid'])) {
  $id= $_GET['sensorid'];
  echo "        var id = \"$id\";";
}
?>

//        var id = "28-0000048e50b5";
        var start = new Date().getTime();
        var minute = 60*1000;
        start = start - (9*60*minute);

			$(document).ready(function() {
				var options = {
					chart: {
						renderTo: 'container',
                                                zoomType: 'x',
					//	defaultSeriesType: 'line',
						marginRight: 130,
						marginBottom: 25
					},
					title: {
						text: 'Hőmérsékleti adatok',
						x: -20 //center
					},
					subtitle: {
						text: '',
						x: -20
					},
					xAxis: {
						type: 'datetime',
				//		tickInterval: 3600 * 1000, // one hour
                                                tickInterval: 10*minute,
						tickWidth: 0,
						gridLineWidth: 1,
						labels: {
							align: 'center',
							x: -3,
							y: 20,
							formatter: function() {
								//return Highcharts.dateFormat('%l%p', this.value);
							}
						}
					},
					yAxis: {
						title: {
							text: 'Hőmérséklet (°C)'
						},
						plotLines: [{
							value: 0,
							width: 1,
							color: '#808080'
						}]
					},
					tooltip: {
                                                valueDecimals: 3,
				//		formatter: function() {
                                       //           return this.x +' '+ this.y;
				  //              return Highcharts.dateFormat('%l%p', this.x-(1000*3600)) +'-'+ Highcharts.dateFormat('%l%p', this.x) +': <b>'+ this.y + '</b>';
				//		}
					},
					legend: {
						layout: 'vertical',
						align: 'right',
						verticalAlign: 'top',
						x: -10,
						y: 100,
						borderWidth: 0
					},
					series: [{
                                                pointStart: start,
                                                pointInterval: minute,

						name: id
					}]
				}
                                var seriesCounter = 0;
                                var names = [
                                        {name: 'device_alias', id: '28.C0108F040000' }
                                ];

				// Load data asynchronously using jQuery. On success, add the data
				// to the options and initiate the chart.
				// This data is obtained by exporting a GA custom report to TSV.
				// http://api.jquery.com/jQuery.get/
				jQuery.get('data_new.php?sensorid='+id, null, function(tsv) {

					var lines = [];
					traffic = [];
					try {
						// split the data return into lines and parse them
						tsv = tsv.split(/\n/g);
						jQuery.each(tsv, function(i, line) {
							line = line.split(/\t/);
                                                        date = line[0];
							//date = Date.parse(line[0] +' UTC');
							traffic.push([
								date,
								parseFloat(line[1].replace(',', ''), 10)
							]);
						});
					} catch (e) {  }
					options.series[0].data = traffic;
					chart = new Highcharts.StockChart(options);
				});
			});
</script>
</head>
<body>

<div id="container" style="width: 100%; height: 400px; margin: 0 auto"></div>
					
</body>
</html>
