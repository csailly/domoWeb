<?php
include_once $_SERVER ['DOCUMENT_ROOT'] . '/utils/CalendarUtils.php';
include_once $_SERVER ['DOCUMENT_ROOT'] . '/utils/Constants.php';
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
		$this->parameterDao->saveParameter( Constants::POELE_MARCHE_FORCEE, $value );
		$this->parameterDao->saveParameter( Constants::POELE_ARRET_FORCE, !$value );
	}
	
	function sendOffForced($value) {
		$this->parameterDao->saveParameter( Constants::POELE_ARRET_FORCE, $value );
		$this->parameterDao->saveParameter( Constants::POELE_MARCHE_FORCEE, !$value );
	}
	
	function sendOnManual() {
		$this->parameterDao->saveParameter( Constants::ORDRE_MANU, Constants::ORDRE_MANU_ON );
	}
	
	function sendOffManual() {
		$this->parameterDao->saveParameter( Constants::ORDRE_MANU, Constants::ORDRE_MANU_OFF );
	}
	
	
	function saveConsForced($value){
		$this->parameterDao->saveParameter( Constants::TEMP_CONSIGNE_MARCHE_FORCEE, $value);
	}
	
	function saveMaxiForced($value){
		$this->parameterDao->saveParameter( Constants::TEMP_MAXI_MARCHE_FORCEE, $value);
	}
	
	function  savePoeleConfiguration($value){
		$this->parameterDao->saveParameter( Constants::POELE_CONFIG, $value);
		$poeleEtat = $this->parameterDao->getParameter( Constants::POELE_ETAT, $value);
		$this->parameterDao->saveParameter( Constants::ORDRE_MANU, $poeleEtat->value);
	}
	


}

?>