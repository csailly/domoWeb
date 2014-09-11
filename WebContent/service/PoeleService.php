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
		$this->parameterDao->saveParameter( 'POELE_ARRET_FORCE', !$value );
	}
	
	function sendOffForced($value) {
		$this->parameterDao->saveParameter( 'POELE_ARRET_FORCE', $value );
		$this->parameterDao->saveParameter( 'POELE_MARCHE_FORCEE', !$value );
	}
	
	function sendOnManual() {
		$this->parameterDao->saveParameter( 'ORDRE_MANU', 'ON' );
	}
	
	function sendOffManual() {
		$this->parameterDao->saveParameter( 'ORDRE_MANU', 'OFF' );
	}
	
	
	function saveConsForced($value){
		$this->parameterDao->saveParameter( 'TEMP_CONSIGNE_MARCHE_FORCEE', $value);
	}
	
	function saveMaxiForced($value){
		$this->parameterDao->saveParameter( 'TEMP_MAXI_MARCHE_FORCEE', $value);
	}
	
	function  savePoeleConfiguration($value){
		$this->parameterDao->saveParameter( 'POELE_CONFIG', $value);
		$poeleEtat = $this->parameterDao->getParameter( 'POELE_ETAT', $value);
		$this->parameterDao->saveParameter( 'ORDRE_MANU', $poeleEtat->value);
	}
	


}

?>