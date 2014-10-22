<?php
include_once $_SERVER ['DOCUMENT_ROOT'] . '/conf/DomoWebConfig.php';

class ExternalService {	
	private $domoWebConfig;
	
	function __construct() {
		$this->domoWebConfig = new DomoWebConfig();
	}
	
		
	function getCurrentTemp() {
		$command = escapeshellcmd($this->domoWebConfig->externalCommandTemp);
		$output = shell_exec($command);
		
		if (!isset($output)){
			$output = 'undefined';
		}
		
		return $output;
	}
	
	function launchPoeleScript(){
		$command = escapeshellcmd($this->domoWebConfig->externalCommandMcz);
		$output = shell_exec($command);
		
		return $output;
	}
	
	function updateWebApp(){
		$command = escapeshellcmd($this->domoWebConfig->externalCommandUpdateWebApp);
		$output = shell_exec($command);
		
		return $output;
	}

}

?>