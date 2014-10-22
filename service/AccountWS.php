<?php
header('Content-Type: text/html; charset=utf-8');

include_once $_SERVER['DOCUMENT_ROOT'].'/conf/DomoWebConfig.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/service/DataService.php';

$dataService = new DataService();

$action = null;
if (isset($_POST["action"])){
	$action = $_POST["action"];
}else{
	http_response_code(500);;
	die("Undefined action");
}

try{
	switch ($action) {
		case "authent" :
			//Get parameters
			$login = $_POST["login"];
			$password = $_POST["password"];
			//Call service		
			$response = $dataService->ckeckLogin ( $login, $password );
			//Send response
			echo json_encode($response);
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