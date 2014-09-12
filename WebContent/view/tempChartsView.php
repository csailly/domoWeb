<?php include_once $_SERVER ['DOCUMENT_ROOT'] . '/include/loginCheck.php'; ?>
<!DOCTYPE html>
<html>

<head>
		<?php include_once $_SERVER ['DOCUMENT_ROOT'] . '/include/header.php'; ?>





<?php

include_once $_SERVER ['DOCUMENT_ROOT'] . '/service/DataService.php';

$dataService = new DataService ( $databaseConnexion );
$result = $dataService->getAllTemperatures ();

date_default_timezone_set ( 'Europe/Paris' );

?>





</head>
<body>
<?php

include_once $_SERVER ['DOCUMENT_ROOT'] . '/include/navbar.php';

?>


<div id="container"
		style="min-width: 310px; height: 400px; margin: 0 auto"></div>




<?php include_once $_SERVER ['DOCUMENT_ROOT'] . '/include/footer.php'; ?>


<script type='text/javascript'>//<![CDATA[ 

$(function () {
        $('#container').highcharts({
            chart: {
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
            	text: 'Salon'
            },
            xAxis: {
                type: 'datetime',
                maxZoom: 1 * 24 * 3600000, // 1 days
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
    
            series: [{
                type: 'area',
                name: 'Temp',
                pointStart: Number(<?php echo $result['startTime'] ?>),
				data: <?php echo json_encode($result['datas'])?>
				
            }]
        });
    });
    

//]]>  

</script>

	<script src="http://code.highcharts.com/highcharts.js"></script>
	<script src="http://code.highcharts.com/modules/exporting.js"></script>
	
	<script>
		$( document ).ready(function() {
			myLoading.hidePleaseWait();
		});
	</script>

</body>

</html>
