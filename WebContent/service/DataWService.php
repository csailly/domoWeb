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
			$periodId = $_POST["periodId"];
			$day = $_POST["day"];
			$startDate = $_POST["startDate"];
			$endDate = $_POST["endDate"];
			$startHour = $_POST["startHour"];
			$endHour = $_POST["endHour"];
			$mode = $_POST["mode"];
			echo $dataService->updatePeriode($periodId, $day, $startDate, $endDate, $startHour, $endHour, $mode);
			break;
		case "deletePeriod":
			$periodId = $_POST["periodId"];
			echo $dataService->deletePeriode($periodId);
			break;
		case "createMode" :
			$label = $_POST["label"];
			$cons = $_POST["cons"];
			$max = $_POST["max"];
			echo $dataService->createMode($label, $cons, $max);
			break;
		case "updateMode" :
			$modeId = $_POST["modeId"];
			$label = $_POST["label"];
			$cons = $_POST["cons"];
			$max = $_POST["max"];
			echo $dataService->updateMode($modeId, $label, $cons, $max);
			break;
		case "deleteMode":
			$periodId = $_POST["modeId"];
			echo $dataService->deleteMode($periodId);
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