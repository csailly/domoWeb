<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<title>- jsFiddle demo</title>

<script type='text/javascript'
	src='http://code.jquery.com/jquery-2.0.2.js'></script>





<style type='text/css'>
</style>


<?php
try
{
	$connexion = new PDO('sqlite:/home/pi/syno/domotique.sqlite');
	$resultats=$connexion->query("SELECT * FROM histo_temp"); // on va chercher tous les membres de la table qu'on trie par ordre croissant
	$resultats->setFetchMode(PDO::FETCH_OBJ); // on dit qu'on veut que le r�sultat soit r�cup�rable sous forme d'objet

	$ligne = $resultats->fetch();
	$startDate = $ligne->date;
	while( $ligne) // on r�cup�re la liste des membres
	{
		//echo ''.$ligne->temp.','; // on affiche les membres
		$datas[] = $ligne->temp;
		$ligne = $resultats->fetch();
	}
	$resultats->closeCursor(); // on ferme le curseur des r�sultats

	$milliseconds = 1000 * strtotime($startDate) + 2 * 3600 * 1000;

}

catch(Exception $e)
{
	echo 'Une erreur est survenue !';
	die();
}
?>


<script type='text/javascript'>//<![CDATA[ 

$(function () {
        $('#container').highcharts({
            chart: {
                zoomType: 'x',
                spacingRight: 20
            },
            title: {
                text: 'Historique temp�ratures'
            },
            subtitle: {
                text: document.ontouchstart === undefined ?
                    'Click and drag in the plot area to zoom in' :
                    'Pinch the chart to zoom in'
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
                    text: 'Temp�rature'
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
                name: 'Temp : ',
                pointInterval:  5 * 60 * 1000, // 5 minutes
                pointStart: Number(<?php echo $milliseconds ?>),//Date.UTC(<?php echo date('Y,m-1,d', strtotime($startDate)); ?>),//Date.UTC(2006, 0, 01),
                data: [<?php echo join($datas, ',') ?>]
            }]
        });
    });
    

//]]>  

</script>


</head>
<body>
	<script src="http://code.highcharts.com/highcharts.js"></script>
	<script src="http://code.highcharts.com/modules/exporting.js"></script>

	<div id="container"
		style="min-width: 310px; height: 400px; margin: 0 auto"></div>


</body>


</html>
