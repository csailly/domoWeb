<title>DomoWeb</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<!-- Bootstrap -->
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet"
	href="http://netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css">
<!-- Optional theme -->
<link rel="stylesheet"
	href="http://netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap-theme.min.css">
<!-- Date picker -->
<link rel="stylesheet" href="/css/datepicker.css" />
<!-- Time picker -->
<link rel="stylesheet" href="/css/bootstrap-timepicker.css" />
<!-- Bootstrap-switch -->
<link rel="stylesheet" href="/css/bootstrap-switch.css" />

<!-- Main css -->
<link rel="stylesheet" href="/css/main.css" />

<?php include_once $_SERVER ['DOCUMENT_ROOT'] . '/config/config.php';?>

<?php 
include_once $_SERVER ['DOCUMENT_ROOT'] . '/service/DataService.php';
include_once $_SERVER ['DOCUMENT_ROOT'] . '/service/PoeleService.php';
include_once $_SERVER ['DOCUMENT_ROOT'] . '/service/ExternalService.php';

$dataService = new DataService($databaseConnexion);
$poeleService = new PoeleService($databaseConnexion);
$externalService = new ExternalService($externalCommandTemp, $externalCommandMcz);
?>