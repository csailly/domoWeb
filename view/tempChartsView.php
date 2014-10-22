<?php include_once $_SERVER ['DOCUMENT_ROOT'] . '/include/loginCheck.php'; ?>
<!DOCTYPE html>
<html>

<head>
		<?php include_once $_SERVER ['DOCUMENT_ROOT'] . '/include/header.php'; ?>
</head>
<body>
<?php
include_once $_SERVER ['DOCUMENT_ROOT'] . '/include/navbar.php';


date_default_timezone_set ( 'Europe/Paris' );
$datetime_1week = new DateTime();
$datetime_1week->sub(new DateInterval('P7D'));

$datetime_1month = new DateTime();
$datetime_1month->sub(new DateInterval('P1M'));

$datetime_1year = new DateTime();
$datetime_1year->sub(new DateInterval('P1Y'));
?>
	<select id="tempChartInterval">
		<option value="<?=$datetime_1week->format('Y-m-d') ?>">1 semaine</option>
		<option value="<?=$datetime_1month->format('Y-m-d') ?>">1 mois</option>
		<option value="<?=$datetime_1year->format('Y-m-d') ?>">1 an</option>
	</select>
	<select id="sonde">
		<option value="1">Sonde 1</option>
		<option value="2">Sonde 2</option>
		<option value="1,2">Toutes</option>
	</select>


	<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>

<?php include_once $_SERVER ['DOCUMENT_ROOT'] . '/include/footer.php'; ?>


	<script src="http://code.highcharts.com/highcharts.js"></script>
	<script src="http://code.highcharts.com/modules/exporting.js"></script>

	<script>
	
		function showGraph(histosList){
			var options = {
		        chart: {
		        	renderTo: 'container',
		            zoomType: 'x',
		            spacingRight: 20
		        },
		        title: {
		            text: 'Historique températures'
		        },
		        subtitle: {
		            //text: document.ontouchstart === undefined ?
		            //    'Click and drag in the plot area to zoom in' :
		            //    'Pinch the chart to zoom in'
		        	//text: 'Salon'
		        },
		        xAxis: {
		            type: 'datetime',
		            maxZoom: 0.5 * 24 * 3600000, // 0.5 days
		            title: {
		                text: null
		            }
		        },
		        yAxis: {
		            title: {
		                text: 'Température'
		            }
		        },
		        tooltip: {
		            shared: true
		        },
		        legend: {
		            enabled: false
		        },
		        plotOptions: {
		            area: {
		                fillColor: {
		                    linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1},
		                    stops: [
		                        [0, Highcharts.getOptions().colors[0]],
		                        [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
		                    ]
		                },
		                lineWidth: 1,
		                marker: {
		                    enabled: false
		                },
		                shadow: false,
		                states: {
		                    hover: {
		                        lineWidth: 1
		                    }
		                },
		                threshold: null
		            }
		        },
		
		        series: []
		    };
	
			for(i=0;i<histosList.length;i++){
				options.series[i] = {
			            type: 'area',
			            name: histosList[i]['sonde'],
			            pointStart: Number(histosList[i]['histos']['startTime']),
						data: histosList[i]['histos']['datas']
						
			    };
			}
			
			chart = new Highcharts.Chart(options);	
		}
	
	
		function refreshHistoTemp(){
			$.post( "/service/DomoWebWS.php", {action : "getHistoTemp", startDate : $('#tempChartInterval').val(), sondes : "["+$('#sonde').val()+"]"})
			.done(	function( data ) {
					var decode = $.parseJSON(data);
					var result = decode.result;
					if (result === "success"){
						showGraph(decode.histosList);		
					}
				});
		}
	
	
		$('#tempChartInterval').change(function(){
			refreshHistoTemp();
		});
		
		$('#sonde').change(function(){
			refreshHistoTemp();
		});
	
	
		$( document ).ready(function() {
			refreshHistoTemp();			
			myLoading.hidePleaseWait();
		});
	</script>

</body>
</html>