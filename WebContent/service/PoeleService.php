<?php
include_once $_SERVER ['DOCUMENT_ROOT'] . '/utils/CalendarUtils.php';
include_once $_SERVER ['DOCUMENT_ROOT'] . '/dao/AccountDAO.php';
include_once $_SERVER ['DOCUMENT_ROOT'] . '/dao/ModeDAO.php';
include_once $_SERVER ['DOCUMENT_ROOT'] . '/dao/ParameterDAO.php';
include_once $_SERVER ['DOCUMENT_ROOT'] . '/dao/PeriodeDAO.php';
include_once $_SERVER ['DOCUMENT_ROOT'] . '/dao/TemperatureDAO.php';
class PoeleService {	
	private $parameterDao;
	function __construct($connexion) {
		$this->parameterDao = new ParameterDAO($connexion);
	}
	
		
	function sendOnForced($value) {
		$response = array (
				'result' => 'success'
		);
		try {
			return $this->parameterDao->saveParameter( 'POELE_MARCHE_FORCEE', $value );
		} catch ( PDOException $e ) {
			$response ['result'] = 'error';
			$errorsMsgs = array ();
			$errorsMsgs ["exception"] = $e->getMessage ();
			$errors = array (
					'errorsMsgs' => $errorsMsgs
			);
			$response ['errors'] = $errors;
		}
	
		return $response;
	}
	
	function sendOffForced($value) {
		$response = array (
				'result' => 'success'
		);
		try {
			return $this->parameterDao->saveParameter( 'POELE_ARRET_FORCE', $value );
		} catch ( PDOException $e ) {
			$response ['result'] = 'error';
			$errorsMsgs = array ();
			$errorsMsgs ["exception"] = $e->getMessage ();
			$errors = array (
					'errorsMsgs' => $errorsMsgs
			);
			$response ['errors'] = $errors;
		}
	
		return $response;
	}
	
	// function test() {
	// $response = array (
	// 'result' => 'success'
	// );
	// try {
	
	// } catch ( PDOException $e ) {
	// $response ['result'] = 'error';
	// $errorsMsgs = array ();
	// $errorsMsgs ["exception"] = $e->getMessage ();
	// $errors = array (
	// 'errorsMsgs' => $errorsMsgs
	// );
	// $response ['errors'] = $errors;
	// }
	
	// return $response;
	// }
}

?>