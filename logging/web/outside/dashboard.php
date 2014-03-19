<?php
require_once('../constants.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="keywords" content="jQuery Gauge, Gauge, Radial Gauge, jqxGauge" />
    <meta name="description" content="Weather station" />
    <meta charset="UTF-8">
    <title id='Description'>Outside weather</title>
    <link rel="stylesheet" href="jqx.base.css" type="text/css" />
    <script type="text/javascript" src="jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="jqxcore.js"></script>
    <script type="text/javascript" src="jqxchart.js"></script>
    <script type="text/javascript" src="jqxgauge.js"></script>
    <style type="text/css">
        #gaugePresValue {
	        background-image: -webkit-gradient(linear, 50% 0%, 50% 100%, color-stop(0%, #fafafa), color-stop(100%, #f3f3f3));
	        background-image: -webkit-linear-gradient(#fafafa, #f3f3f3);
	        background-image: -moz-linear-gradient(#fafafa, #f3f3f3);
	        background-image: -o-linear-gradient(#fafafa, #f3f3f3);
	        background-image: -ms-linear-gradient(#fafafa, #f3f3f3);
	        background-image: linear-gradient(#fafafa, #f3f3f3);
	        -webkit-border-radius: 3px;
	        -moz-border-radius: 3px;
	        -ms-border-radius: 3px;
	        -o-border-radius: 3px;
	        border-radius: 3px;
	        -webkit-box-shadow: 0 0 50px rgba(0, 0, 0, 0.2);
	        -moz-box-shadow: 0 0 50px rgba(0, 0, 0, 0.2);
	        box-shadow: 0 0 50px rgba(0, 0, 0, 0.2);
	        padding: 10px;
	    }
    </style>
    <script type="text/javascript">
        $(document).ready(function () {

<?php
  $output=null;
  $command="rrdtool lastupdate $sensors_settings_path/pressure-outside.rrd |tail -1|cut -c 13-";
  exec ($command, $output);
?>
            $('#gaugePresContainer').jqxGauge({
                ranges: [{ startValue: 950, endValue: 965, style: { fill: '#ff8000', stroke: '#ff8000' }, endWidth: 10, startWidth: 13 }, //stormy
                         { startValue: 965, endValue: 985, style: { fill: '#fbd109', stroke: '#fbd109' }, endWidth: 5, startWidth: 10 }, //rain
                         { startValue: 985, endValue: 1015, style: { fill: '#4bb648', stroke: '#4bb648' }, endWidth: 5, startWidth: 5 }, //change
                         { startValue: 1015, endValue: 1035, style: { fill: '#ff8000', stroke: '#ff8000' }, endWidth: 10, startWidth: 5 }, //fair
                         { startValue: 1035, endValue: 1050, style: { fill: '#e02629', stroke: '#e02629' }, endWidth: 13, startWidth: 10 }], //very dry
                ticksMinor: { interval: 5, size: '5%' },
                ticksMajor: { interval: 10, size: '9%' },
                caption: { offset: [0, -25], value: 'Barometer <br>'+ <?=$output[0];?> + ' hPa', position: 'bottom' },
                value: 950,
                min: 950,
                max: 1050,
                colorScheme: 'scheme04',
                animationDuration: 1200
            });
            $('#gaugePresContainer').on('valueChanging', function (e) {
                $('#gaugePresValue').text(Math.round(e.args.value) + ' hPa');
            });
            $('#gaugePresContainer').jqxGauge('value', <?=$output[0];?>);

<?php
  $output=null;
  $command="rrdtool lastupdate $sensors_settings_path/temperature-outside.rrd |tail -1|cut -c 13-";
  exec ($command, $output);
?>
            $('#linearGauge').jqxLinearGauge({
                orientation: 'vertical',
                width: 100,
                height: 350,
                ticksMajor: { size: '10%', interval: 10 },
                ticksMinor: { size: '5%', interval: 2.5, style: { 'stroke-width': 1, stroke: '#aaaaaa'} },
                min: -20,
                max: 40,
                pointer: { size: '5%' },
                colorScheme: 'scheme02',
                labels: { interval: 10, formatValue: function (value, position) {
                    if (position === 'far') {
                        value = (9 / 5) * value + 32;
                        if (value === -4) {
                            return '°F';
                        }
                        return value + '°';
                    }
                    if (value === -20) {
                        return '°C';
                    }
                    return value + '°';
                }
                },
                ranges: [
                { startValue: -10, endValue: 10, style: { fill: '#FFF157', stroke: '#FFF157'} },
                { startValue: 10, endValue: 35, style: { fill: '#FFA200', stroke: '#FFA200'} },
                { startValue: 35, endValue: 40, style: { fill: '#FF4800', stroke: '#FF4800'}}],
                animationDuration: 1200
            });
            $('#linearGauge').jqxLinearGauge('value', <?=$output[0];?>);


<?php
  $output=null;
  $command="rrdtool lastupdate $sensors_settings_path/humidity-outside.rrd |tail -1|cut -c 13-";
  exec ($command, $output);
?>
            $('#gaugeHumContainer').jqxGauge({
                ranges: [{ startValue: 0, endValue: 35, style: { fill: '#fbd109', stroke: '#fbd109' }, endWidth: 5, startWidth: 13 }, //very dry
                         { startValue: 35, endValue: 75, style: { fill: '#4bb648', stroke: '#4bb648' }, endWidth: 5, startWidth: 5 }, //normal
                         { startValue: 75, endValue: 100, style: { fill: '#fbd109', stroke: '#fbd109' }, endWidth: 13, startWidth: 5 }, //humid
                         { startValue: 1015, endValue: 1035, style: { fill: '#ff8000', stroke: '#ff8000' }, endWidth: 10, startWidth: 5 }, //fair
                         { startValue: 1035, endValue: 1050, style: { fill: '#e02629', stroke: '#e02629' }, endWidth: 13, startWidth: 10 }], //very dry
                ticksMinor: { interval: 5, size: '5%' },
                ticksMajor: { interval: 10, size: '9%' },
	        caption: { offset: [0, -25], value: 'Humidity <br>'+ <?=$output[0];?> + ' %', position: 'bottom' },
                value: 0,
                max: 100,
                colorScheme: 'scheme05',
                animationDuration: 1200
            });
            $('#gaugeHumContainer').on('valueChanging', function (e) {
                $('#gaugeHumValue').text(Math.round(e.args.value) + ' %');
            });
            $('#gaugeHumContainer').jqxGauge('value', <?=$output[0];?>);

        });
    </script>
</head>
<body style="background:white;">
    <div id="demoWidget" style="position: relative;">
	    <div style="float: left;" id="gaugePresContainer"></div>
        <div id="gaugePresValue" style="position: absolute; top: 255px; left: 122px; font-family: Sans-Serif; text-align: center; font-size: 18px; width: 80px;"></div>
        <div style="margin-left: 60px; float: left;" id="linearGauge"></div>
        <div style="margin-left: 60px; float: left;" id="gaugeHumContainer"></div>
        <div id="gaugeHumValue" style="position: absolute; top: 255px; left: 702px; font-family: Sans-Serif; text-align: center; font-size: 18px; width: 80px;"></div>

    </div>
</body>
</html>
