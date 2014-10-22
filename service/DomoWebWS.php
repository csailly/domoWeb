<?php
header ( 'Content-Type: text/html; charset=utf-8' );

include_once $_SERVER ['DOCUMENT_ROOT'] . '/conf/DomoWebConfig.php';
include_once $_SERVER ['DOCUMENT_ROOT'] . '/service/DataService.php';
include_once $_SERVER ['DOCUMENT_ROOT'] . '/service/PoeleService.php';
include_once $_SERVER ['DOCUMENT_ROOT'] . '/service/ExternalService.php';
include_once $_SERVER ['DOCUMENT_ROOT'] . '/utils/Constants.php';


date_default_timezone_set ( 'Europe/Paris' );


class DomoWebWs {
	private $dataService;
	private $poeleService;
	private $externalService;
	
	function __construct($domoWebConfig){
		$this->dataService = new DataService ();
		$this->poeleService = new PoeleService ( );
		$this->externalService = new ExternalService ();
	}

	function createPeriod(){
		// Call service
		try {
			$response = $this->dataService->createPeriode ( $_POST ["day"], $_POST ["startDate"], $_POST ["endDate"], $_POST ["startHour"], $_POST ["endHour"], $_POST ["mode"] );
		} catch ( PDOException $e ) {
			$response ['result'] = 'error';
			$errorsMsgs = array ();
			$errorsMsgs ["exception"] = $e->getMessage ();
			$errors = array (
					'errorsMsgs' => $errorsMsgs
			);
			$response ['errors'] = $errors;
		}
		
		// Send response
		return json_encode ( $response );
	}

	function updatePeriod(){
		// Get parameters
		$periodId = $_POST ["periodId"];
		$day = $_POST ["day"];
		$startDate = $_POST ["startDate"];
		$endDate = $_POST ["endDate"];
		$startHour = $_POST ["startHour"];
		$endHour = $_POST ["endHour"];
		$mode = $_POST ["mode"];
		// Call service
		try {
			$response = $this->dataService->updatePeriode ( $periodId, $day, $startDate, $endDate, $startHour, $endHour, $mode );
		} catch ( PDOException $e ) {
			$response ['result'] = 'error';
			$errorsMsgs = array ();
			$errorsMsgs ["exception"] = $e->getMessage ();
			$errors = array (
					'errorsMsgs' => $errorsMsgs
			);
			$response ['errors'] = $errors;
		}
		
		// Send response
		return json_encode ( $response );
	}
	
	function deletePeriod(){
		// Get parameters
		$periodId = $_POST ["periodId"];
		// Call service
		$response = array (
				'result' => 'success'
		);
		try {
			$this->dataService->deletePeriode ( $periodId );
		} catch ( PDOException $e ) {
			$response ['result'] = 'error';
			$errorsMsgs = array ();
			$errorsMsgs ["exception"] = $e->getMessage ();
			$errors = array (
					'errorsMsgs' => $errorsMsgs
			);
			$response ['errors'] = $errors;
		}
		
		// Send response
		return json_encode ( $response );
	}
	
	function createMode(){
		// Get parameters
		$label = $_POST ["label"];
		$cons = $_POST ["cons"];
		$max = $_POST ["max"];
		// Call service
		// TODO check parameters
		$response = array (
				'result' => 'success'
		);
		try {
			$this->dataService->createMode ( $label, $cons, $max );
		} catch ( PDOException $e ) {
			$response ['result'] = 'error';
			$errorsMsgs = array ();
			$errorsMsgs ["exception"] = $e->getMessage ();
			$errors = array (
					'errorsMsgs' => $errorsMsgs
			);
			$response ['errors'] = $errors;
		}
		
		// Send response
		return json_encode ( $response );
	}
	
	function updateMode(){
		// Get parameters
		$modeId = $_POST ["modeId"];
		$label = $_POST ["label"];
		$cons = $_POST ["cons"];
		$max = $_POST ["max"];
		// Call service
		// TODO check parameters
		$response = array (
				'result' => 'success'
		);
		try {
			$this->dataService->updateMode ( $modeId, $label, $cons, $max );
		} catch ( PDOException $e ) {
			$response ['result'] = 'error';
			$errorsMsgs = array ();
			$errorsMsgs ["exception"] = $e->getMessage ();
			$errors = array (
					'errorsMsgs' => $errorsMsgs
			);
			$response ['errors'] = $errors;
		}
		
		// Send response
		return json_encode ( $response );
	}
	
	function deleteMode(){
		// Get parameters
		$periodId = $_POST ["modeId"];
		// Call service
		$response = array (
				'result' => 'success'
		);
		try {
			$this->dataService->deleteMode ( $periodId );
		} catch ( PDOException $e ) {
			$response ['result'] = 'error';
			$errorsMsgs = array ();
			$errorsMsgs ["exception"] = $e->getMessage ();
			$errors = array (
					'errorsMsgs' => $errorsMsgs
			);
			$response ['errors'] = $errors;
		}
		// Send response
		return json_encode ( $response );
	}
	
	function saveOnOrder(){
		// Get parameters
		$value = $_POST ["value"];
		// Call service
		$response = array (
				'result' => 'success'
		);
		try {
			$poeleConfig = $this->dataService->getParameter ( Constants::POELE_CONFIG )->value;
		
			if($poeleConfig === Constants::POELE_CONFIG_AUTO){
				$this->poeleService->sendOnForced ( $value == "true" ? Constants::POELE_MARCHE_FORCEE_ON : Constants::POELE_MARCHE_FORCEE_OFF );
				$response ['value'] = $this->externalService->launchPoeleScript ();
			}elseif ($poeleConfig == Constants::POELE_CONFIG_MANU){
				$this->poeleService->sendOnManual();
				$response ['value'] = $this->externalService->launchPoeleScript ();
			}
		
		} catch ( PDOException $e ) {
			$response ['result'] = 'error';
			$errorsMsgs = array ();
			$errorsMsgs ["exception"] = $e->getMessage ();
			$errors = array (
					'errorsMsgs' => $errorsMsgs
			);
			$response ['errors'] = $errors;
		}
		// Send response
		return json_encode ( $response );
	}
	
	function saveOffOrder(){
		// Get parameters
		$value = $_POST ["value"];
		// Call services
		$response = array (
				'result' => 'success'
		);
		try {
			$poeleConfig = $this->dataService->getParameter ( Constants::POELE_CONFIG )->value;
			if($poeleConfig === Constants::POELE_CONFIG_AUTO){
				$this->poeleService->sendOffForced ( $value == "true" ? Constants::POELE_ARRET_FORCE_ON : Constants::POELE_ARRET_FORCE_OFF );
				$response ['value'] = $this->externalService->launchPoeleScript ();
			}elseif ($poeleConfig == Constants::POELE_CONFIG_MANU){
				$this->poeleService->sendOffManual();
				$response ['value'] = $this->externalService->launchPoeleScript ();
			}
		} catch ( PDOException $e ) {
			$response ['result'] = 'error';
			$errorsMsgs = array ();
			$errorsMsgs ["exception"] = $e->getMessage ();
			$errors = array (
					'errorsMsgs' => $errorsMsgs
			);
			$response ['errors'] = $errors;
		}
		// Send response
		return json_encode ( $response );
	}
	
	function saveConsForced(){
		// Get parameters
		$value = $_POST ["value"];
		// Call services
		$response = array (
				'result' => 'success'
		);
		try {
			$this->poeleService->saveConsForced ( $value );
		} catch ( PDOException $e ) {
			$response ['result'] = 'error';
			$errorsMsgs = array ();
			$errorsMsgs ["exception"] = $e->getMessage ();
			$errors = array (
					'errorsMsgs' => $errorsMsgs
			);
			$response ['errors'] = $errors;
		}
		// Send response
		return json_encode ( $response );
	}
	
	function saveMaxiForced(){
		// Get parameters
		$value = $_POST ["value"];
		// Call services
		$response = array (
				'result' => 'success'
		);
		try {
			$this->poeleService->saveMaxiForced ( $value );
		} catch ( PDOException $e ) {
			$response ['result'] = 'error';
			$errorsMsgs = array ();
			$errorsMsgs ["exception"] = $e->getMessage ();
			$errors = array (
					'errorsMsgs' => $errorsMsgs
			);
			$response ['errors'] = $errors;
		}
		// Send response
		return json_encode ( $response );
	}
	
	function readCurrentTemp(){
		// Call services
		$response = array (
				'result' => 'success'
		);
		try {
			$currentTemp = $this->externalService->getCurrentTemp ();
			$response ['currentTemp'] = $currentTemp;
		} catch ( PDOException $e ) {
			$response ['result'] = 'error';
			$errorsMsgs = array ();
			$errorsMsgs ["exception"] = $e->getMessage ();
			$errors = array (
					'errorsMsgs' => $errorsMsgs
			);
			$response ['errors'] = $errors;
		}
		// Send response
		return json_encode ( $response );
	}
	
	function readStoveStatus(){
		// Call services
		$response = array (
				'result' => 'success'
		);
		try {
			$poeleStatus = $this->dataService->getParameter ( Constants::POELE_ETAT )->value;
			$response ['poeleStatus'] = $poeleStatus;
		} catch ( PDOException $e ) {
			$response ['result'] = 'error';
			$errorsMsgs = array ();
			$errorsMsgs ["exception"] = $e->getMessage ();
			$errors = array (
					'errorsMsgs' => $errorsMsgs
			);
			$response ['errors'] = $errors;
		}
		// Send response
		return json_encode ( $response );
	}
	
	function readStoveConfig(){
		// Call services
		$response = array (
				'result' => 'success'
		);
		try {
			$poeleConfig = $this->dataService->getParameter ( Constants::POELE_CONFIG )->value;
			$response ['poeleConfig'] = $poeleConfig;
		} catch ( PDOException $e ) {
			$response ['result'] = 'error';
			$errorsMsgs = array ();
			$errorsMsgs ["exception"] = $e->getMessage ();
			$errors = array (
					'errorsMsgs' => $errorsMsgs
			);
			$response ['errors'] = $errors;
		}
		// Send response
		return json_encode ( $response );
	}
	
	function saveStoveConfig(){
		// Get parameters
		$value = $_POST ["value"];
			
		// Call services
		$response = array (
				'result' => 'success'
		);
		try {
			$poeleConfig = $this->poeleService->savePoeleConfiguration($value);
			$response ['value'] = $this->externalService->launchPoeleScript ();
		} catch ( PDOException $e ) {
			$response ['result'] = 'error';
			$errorsMsgs = array ();
			$errorsMsgs ["exception"] = $e->getMessage ();
			$errors = array (
					'errorsMsgs' => $errorsMsgs
			);
			$response ['errors'] = $errors;
		}
		// Send response
		return json_encode ( $response );
	}
	
	function readCurrentPeriodAndMode(){
		// Call services
		$response = array (
				'result' => 'success'
		);
		try {
			$poeleConfig = $this->dataService->getParameter ( Constants::POELE_CONFIG )->value;
			if($poeleConfig === Constants::POELE_CONFIG_AUTO){
				$currentPeriode = $this->dataService->getCurrentPeriode ();
				$currentMode = null;
				if ($currentPeriode != null){
					$currentMode = $this->dataService->getModeById ( $currentPeriode->modeId );
				}
				$response ['currentPeriode'] = $currentPeriode;
				$response ['currentMode'] = $currentMode;
			}else if($poeleConfig === Constants::POELE_CONFIG_MANU){
				$max = $this->dataService->getParameter ( Constants::TEMP_MAXI_MARCHE_FORCEE )->value;
				$cons = $this->dataService->getParameter(Constants::TEMP_CONSIGNE_MARCHE_FORCEE)->value;
		
				$currentMode  = new Mode(-1, 'Manuel', $cons, $max);
		
				$response ['currentPeriode'] = null;
				$response ['currentMode'] = $currentMode;
			}
		} catch ( PDOException $e ) {
			$response ['result'] = 'error';
			$errorsMsgs = array ();
			$errorsMsgs ["exception"] = $e->getMessage ();
			$errors = array (
					'errorsMsgs' => $errorsMsgs
			);
			$response ['errors'] = $errors;
		}
		// Send response
		return json_encode ( $response );
	}
	
	function saveParameter(){
		// Get parameters
		$code = $_POST ["code"];
		$value = $_POST ["value"];
		// Call services
		$response = array (
				'result' => 'success'
		);
		try {
			$this->dataService->saveParameter($code, $value);
		} catch ( PDOException $e ) {
			$response ['result'] = 'error';
			$errorsMsgs = array ();
			$errorsMsgs ["exception"] = $e->getMessage ();
			$errors = array (
					'errorsMsgs' => $errorsMsgs
			);
			$response ['errors'] = $errors;
		}
		// Send response
		return json_encode ( $response );
	}
	
	function readTempHisto(){
		// Get parameters
		$startDate = $_POST ["startDate"];
		$sondes = json_decode($_POST ["sondes"]);
		// Call services
		$response = array (
				'result' => 'success'
		);
		try {
			$result = $this->dataService->getAllTemperatures (new DateTime($startDate), $sondes);
			$response ['histosList'] = $result;
		} catch ( PDOException $e ) {
			$response ['result'] = 'error';
			$errorsMsgs = array ();
			$errorsMsgs ["exception"] = $e->getMessage ();
			$errors = array (
					'errorsMsgs' => $errorsMsgs
			);
			$response ['errors'] = $errors;
		}
		// Send response
		return json_encode ( $response );
	}
	
	function updateDomoWeb(){
		// Call services
		$response = array (
				'result' => 'success'
		);
		$response ['value'] = $this->externalService->updateWebApp ();
		// Send response
		return json_encode ( $response );
	}
	
}





$action = null;
if (isset ( $_POST ["action"] )) {
	$action = $_POST ["action"];
} else {
	http_response_code ( 500 );
	die ( "Undefined action" );
}

$domoWebConfig = new DomoWebConfig();
$domoWebWs = new DomoWebWs($domoWebConfig);

try {
	switch ($action) {
		case "createPeriod" :
			echo $domoWebWs->createPeriod();
			break;
		case "updatePeriod" :
			echo $domoWebWs->updatePeriod();
			break;
		case "deletePeriod" :
			echo $domoWebWs->deletePeriod();
			break;
		case "createMode" :
			echo $domoWebWs->createMode();
			break;
		case "updateMode" :
			echo $domoWebWs->updateMode();
			break;
		case "deleteMode" :
			echo $domoWebWs->deleteMode();
			break;
		case "onOrder" :
			echo $domoWebWs->saveOnOrder();
			break;
		case "offOrder" :
			echo $domoWebWs->saveOffOrder();
			break;
		case "upConsForced" :
			echo $domoWebWs->saveConsForced();
			break;
		case "downConsForced" :
			echo $domoWebWs->saveConsForced();
			break;
		case "upMaxiForced" :
			echo $domoWebWs->saveMaxiForced();
			break;
		case "downMaxiForced" :
			echo $domoWebWs->saveMaxiForced();
			break;
		case "readCurrentTemp" :
			echo $domoWebWs->readCurrentTemp();
			break;
		case "readPoeleStatus" :
			echo $domoWebWs->readStoveStatus();
			break;
		case "readPoeleConfiguration" :
			echo $domoWebWs->readStoveConfig();
			break;
		case "savePoeleConfiguration" :
			echo $domoWebWs->saveStoveConfig();
			break;
		case "readCurrentPeriodeAndMode" :
			echo $domoWebWs->readCurrentPeriodAndMode();	
			break;
		case "saveParameter":
			echo $domoWebWs->saveParameter();
			break;
		case "getHistoTemp":
			echo $domoWebWs->readTempHisto();
			break;
		case "updateWebApp" :
			echo $domoWebWs->updateDomoWeb();	
			break;
		default :
			http_response_code ( 500 );
			die ( "Bad action" );
	}
} catch ( Exception $e ) {
	echo 'Exception reçue : ', $e->getMessage (), "\n";
	http_response_code ( 500 );
}

?>