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
		$this->parameterDao->saveParameter( 'POELE_MARCHE_FORCEE', $value );
	}
	
	function sendOffForced($value) {
		$this->parameterDao->saveParameter( 'POELE_ARRET_FORCE', $value );
	}
	
	
	function saveConsForced($value){
		$this->parameterDao->saveParameter( 'TEMP_CONSIGNE_MARCHE_FORCEE', $value);
	}
	

	
	
	function saveMaxiForced($value){
		$this->parameterDao->saveParameter( 'TEMP_MAXI_MARCHE_FORCEE', $value);
	}
	


}

?>