<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/config/config.ini';
include_once $_SERVER['DOCUMENT_ROOT'].'/service/DataService.php';

$dataService = new DataService($databaseConnexion);

$action = null;
if (isset($_POST["action"])){
	$action = $_POST["action"];
}else{
	http_response_code(500);;
	die("Undefined action");
}

try{
	switch ($action) {
		case "createPeriod" :
			$day = $_POST["day"];
			$startDate = $_POST["startDate"];
			$endDate = $_POST["endDate"];
			$startHour = $_POST["startHour"];
			$endHour = $_POST["endHour"];
			$mode = $_POST["mode"];
			echo $dataService->createPeriode($day, $startDate, $endDate, $startHour, $endHour, $mode);
			break;
		case "updatePeriod" :
			break;
		case "deletePeriod":
			$periodId = $_POST["periodId"];
			echo $dataService->deletePeriode($periodId);
			break;
		default :
			http_response_code(500);
			die("Bad action");
	}
}catch(Exception $e){
	echo 'Exception reçue : ',  $e->getMessage(), "\n";
	http_response_code(500);
}

?>