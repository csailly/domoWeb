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
			//Get parameters
			$day = $_POST["day"];
			$startDate = $_POST["startDate"];
			$endDate = $_POST["endDate"];
			$startHour = $_POST["startHour"];
			$endHour = $_POST["endHour"];
			$mode = $_POST["mode"];
			//Call service		
			$response =$dataService->createPeriode($day, $startDate, $endDate, $startHour, $endHour, $mode);
			//Send response
			echo json_encode($response);
			break;
		case "updatePeriod" :
			//Get parameters
			$periodId = $_POST["periodId"];
			$day = $_POST["day"];
			$startDate = $_POST["startDate"];
			$endDate = $_POST["endDate"];
			$startHour = $_POST["startHour"];
			$endHour = $_POST["endHour"];
			$mode = $_POST["mode"];
			//Call service
			$response = $dataService->updatePeriode($periodId, $day, $startDate, $endDate, $startHour, $endHour, $mode);
			//Send response
			echo json_encode($response);
			break;
		case "deletePeriod":
			//Get parameters
			$periodId = $_POST["periodId"];
			//Call service
			$response = $dataService->deletePeriode($periodId);
			//Send response
			echo json_encode ( $response );
			break;
		case "createMode" :
			//Get parameters
			$label = $_POST["label"];
			$cons = $_POST["cons"];
			$max = $_POST["max"];
			//Call service
			$response =  $dataService->createMode($label, $cons, $max);
			//Send response
			echo json_encode ( $response );
			break;
		case "updateMode" :
			//Get parameters
			$modeId = $_POST["modeId"];
			$label = $_POST["label"];
			$cons = $_POST["cons"];
			$max = $_POST["max"];
			//Call service
			$response =  $dataService->updateMode($modeId, $label, $cons, $max);
			//Send response
			echo json_encode ( $response );
			break;
		case "deleteMode":
			//Get parameters
			$periodId = $_POST["modeId"];
			//Call service
			$response =  $dataService->deleteMode($periodId);
			//Send response
			echo json_encode ( $response );
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