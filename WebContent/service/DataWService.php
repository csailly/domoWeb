<?php
header ( 'Content-Type: text/html; charset=utf-8' );

include_once $_SERVER ['DOCUMENT_ROOT'] . '/config/config.php';
include_once $_SERVER ['DOCUMENT_ROOT'] . '/service/DataService.php';
include_once $_SERVER ['DOCUMENT_ROOT'] . '/service/PoeleService.php';
include_once $_SERVER ['DOCUMENT_ROOT'] . '/service/ExternalService.php';
include_once $_SERVER ['DOCUMENT_ROOT'] . '/utils/Constants.php';

$dataService = new DataService ( $databaseConnexion );
$poeleService = new PoeleService ( $databaseConnexion );
$externalService = new ExternalService ( $externalCommandTemp, $externalCommandMcz );

$action = null;
if (isset ( $_POST ["action"] )) {
	$action = $_POST ["action"];
} else {
	http_response_code ( 500 );
	;
	die ( "Undefined action" );
}

try {
	switch ($action) {
		case "createPeriod" :
			// Get parameters
			$day = $_POST ["day"];
			$startDate = $_POST ["startDate"];
			$endDate = $_POST ["endDate"];
			$startHour = $_POST ["startHour"];
			$endHour = $_POST ["endHour"];
			$mode = $_POST ["mode"];
			// Call service
			$response = $dataService->createPeriode ( $day, $startDate, $endDate, $startHour, $endHour, $mode );
			// Send response
			$json = json_encode ( $response );
			echo $json;
			break;
		case "updatePeriod" :
			// Get parameters
			$periodId = $_POST ["periodId"];
			$day = $_POST ["day"];
			$startDate = $_POST ["startDate"];
			$endDate = $_POST ["endDate"];
			$startHour = $_POST ["startHour"];
			$endHour = $_POST ["endHour"];
			$mode = $_POST ["mode"];
			// Call service
			$response = $dataService->updatePeriode ( $periodId, $day, $startDate, $endDate, $startHour, $endHour, $mode );
			// Send response
			echo json_encode ( $response );
			break;
		case "deletePeriod" :
			// Get parameters
			$periodId = $_POST ["periodId"];
			// Call service
			$response = $dataService->deletePeriode ( $periodId );
			// Send response
			echo json_encode ( $response );
			break;
		case "createMode" :
			// Get parameters
			$label = $_POST ["label"];
			$cons = $_POST ["cons"];
			$max = $_POST ["max"];
			// Call service
			$response = $dataService->createMode ( $label, $cons, $max );
			// Send response
			echo json_encode ( $response );
			break;
		case "updateMode" :
			// Get parameters
			$modeId = $_POST ["modeId"];
			$label = $_POST ["label"];
			$cons = $_POST ["cons"];
			$max = $_POST ["max"];
			// Call service
			$response = $dataService->updateMode ( $modeId, $label, $cons, $max );
			// Send response
			echo json_encode ( $response );
			break;
		case "deleteMode" :
			// Get parameters
			$periodId = $_POST ["modeId"];
			// Call service
			$response = $dataService->deleteMode ( $periodId );
			// Send response
			echo json_encode ( $response );
			break;
		case "onOrder" :
			// Get parameters
			$value = $_POST ["value"];
			// Call service
			$response = array (
					'result' => 'success' 
			);
			try {
				$poeleConfig = $dataService->getParameter ( Constants::POELE_CONFIG )->value;
				
				if($poeleConfig === Constants::POELE_CONFIG_AUTO){				
					$poeleService->sendOnForced ( $value == "true" ? Constants::POELE_MARCHE_FORCEE_ON : Constants::POELE_MARCHE_FORCEE_OFF );
					$response ['value'] = $externalService->launchPoeleScript ();
				}elseif ($poeleConfig == Constants::POELE_CONFIG_MANU){
					$poeleService->sendOnManual();
					$response ['value'] = $externalService->launchPoeleScript ();
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
			echo json_encode ( $response );
			break;
		case "offOrder" :
			// Get parameters
			$value = $_POST ["value"];
			// Call services
			$response = array (
					'result' => 'success' 
			);
			try {
				$poeleConfig = $dataService->getParameter ( Constants::POELE_CONFIG )->value;
				if($poeleConfig === Constants::POELE_CONFIG_AUTO){
					$poeleService->sendOffForced ( $value == "true" ? Constants::POELE_ARRET_FORCE_ON : Constants::POELE_ARRET_FORCE_OFF );
					$response ['value'] = $externalService->launchPoeleScript ();
				}elseif ($poeleConfig == Constants::POELE_CONFIG_MANU){
					$poeleService->sendOffManual();
					$response ['value'] = $externalService->launchPoeleScript ();
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
			echo json_encode ( $response );
			break;
		case "upConsForced" :
			// Get parameters
			$value = $_POST ["value"];
			// Call services
			$response = array (
					'result' => 'success' 
			);
			try {
				$poeleService->saveConsForced ( $value );
				//$externalService->launchPoeleScript ();
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
			echo json_encode ( $response );
			break;
		case "downConsForced" :
			// Get parameters
			$value = $_POST ["value"];
			// Call services
			$response = array (
					'result' => 'success' 
			);
			try {
				$poeleService->saveConsForced ( $value );
				//$externalService->launchPoeleScript ();
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
			echo json_encode ( $response );
			break;
		case "upMaxiForced" :
			// Get parameters
			$value = $_POST ["value"];
			// Call services
			$response = array (
					'result' => 'success' 
			);
			try {
				$poeleService->saveMaxiForced ( $value );
				//$externalService->launchPoeleScript ();
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
			echo json_encode ( $response );
			break;
		case "downMaxiForced" :
			// Get parameters
			$value = $_POST ["value"];
			// Call services
			$response = array (
					'result' => 'success' 
			);
			try {
				$poeleService->saveMaxiForced ( $value );
				//$externalService->launchPoeleScript ();
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
			echo json_encode ( $response );
			break;
		case "readCurrentTemp" :
			// Call services
			$response = array (
					'result' => 'success' 
			);
			try {
				$currentTemp = $externalService->getCurrentTemp ();
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
			echo json_encode ( $response );
			break;
		case "readPoeleStatus" :
			// Call services
			$response = array (
					'result' => 'success' 
			);
			try {
				$poeleStatus = $dataService->getParameter ( Constants::POELE_ETAT )->value;
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
			echo json_encode ( $response );
			break;
		case "readPoeleConfiguration" :
			// Call services
			$response = array (
					'result' => 'success' 
			);
			try {
				$poeleConfig = $dataService->getParameter ( Constants::POELE_CONFIG )->value;
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
			echo json_encode ( $response );
			break;
		case "savePoeleConfiguration" :
			// Get parameters
			$value = $_POST ["value"];
			//TODO Check parameter
			
			// Call services
			$response = array (
					'result' => 'success' 
			);
			try {
				$poeleConfig = $poeleService->savePoeleConfiguration($value);
				$response ['value'] = $externalService->launchPoeleScript ();				
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
			echo json_encode ( $response );
			break;
		case "readCurrentPeriodeAndMode" :
				// Call services
				$response = array (
						'result' => 'success'
				);
				try {
					$poeleConfig = $dataService->getParameter ( Constants::POELE_CONFIG )->value;
					if($poeleConfig === Constants::POELE_CONFIG_AUTO){										
						$currentPeriode = $dataService->getCurrentPeriode ();
						$currentMode = null;
						if ($currentPeriode != null){
							$currentMode = $dataService->getModeById ( $currentPeriode->modeId );
						}
						$response ['currentPeriode'] = $currentPeriode;
						$response ['currentMode'] = $currentMode;
					}else if($poeleConfig === Constants::POELE_CONFIG_MANU){						
						$max = $dataService->getParameter ( Constants::TEMP_MAXI_MARCHE_FORCEE )->value;
						$cons = $dataService->getParameter(Constants::TEMP_CONSIGNE_MARCHE_FORCEE)->value;
						
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
				echo json_encode ( $response );
				break;
		case "saveParameter":
			// Get parameters
			$code = $_POST ["code"];
			$value = $_POST ["value"];
			// Call services
			$response = array (
					'result' => 'success'
			);
			try {
				$dataService->saveParameter($code, $value);
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
			echo json_encode ( $response );
			break;
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