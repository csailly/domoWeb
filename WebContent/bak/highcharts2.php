<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<title>Historique température</title>

<script type='text/javascript'
	src='http://code.jquery.com/jquery-2.0.2.js'></script>





<style type='text/css'>
</style>


<?php
date_default_timezone_set('Europe/Paris');

$script_tz = date_default_timezone_get();
$ini_tz = ini_get('date.timezone');

if (strcmp($script_tz, ini_get('date.timezone'))){
	echo 'Le décalage horaire du script ('.$script_tz.') diffère du décalage horaire défini dans le fichier ini ('.$ini_tz.').';
} else {
	echo 'Le décalage horaire du script est équivalent à celui défini dans le fichier ini.';
}

try
{
	//$connexion = new PDO('sqlite:'.dirname(__FILE__).'/domotique.sqlite');
	$connexion = new PDO('sqlite:/home/pi/syno/domotique.sqlite');
	$resultats=$connexion->query("SELECT * FROM histo_temp h order by date, heure"); // on va chercher tous les membres de la table qu'on trie par ordre croissant
	$resultats->setFetchMode(PDO::FETCH_OBJ); // on dit qu'on veut que le résultat soit récupérable sous forme d'objet

	$ligne = $resultats->fetch();
	$startDateTime = $ligne->date.'T'.$ligne->heure.' UTC';
	$milliseconds = 1000 * strtotime($startDateTime);
	echo '<br/>'.$milliseconds.'<br/>';

	while( $ligne) // on récupère la liste des membres
	{
		//echo ''.$ligne->temp.','; // on affiche les membres
		$datas[] = $ligne->temp;
		$datasbis[] = array(1000 * strtotime($ligne->date.'T'.$ligne->heure.' UTC'), 1*$ligne->temp);

		$ligne = $resultats->fetch();
	}
	$resultats->closeCursor(); // on ferme le curseur des résultats



}

catch(Exception $e)
{
	echo 'Une erreur est survenue !';
	die();
}

//echo json_encode($datasbis);
//print_r($datasbis) ;

?>


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
                //pointInterval:  5 * 60 * 1000, // 5 minutes
                pointStart: Number(<?php echo $milliseconds ?>),
                //data: [<?php echo join($datas, ',') ?>]
				data: <?php echo json_encode($datasbis) ?>
				
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
