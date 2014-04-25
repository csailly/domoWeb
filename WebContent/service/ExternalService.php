<?php
include_once $_SERVER ['DOCUMENT_ROOT'] . '/config/config.php';
class ExternalService {	
	
	function __construct($externalCommandTemp, $externalCommandMcz) {
		$this->externalCommandTemp = $externalCommandTemp;
		$this->externalCommandMcz = $externalCommandMcz;
	}
	
		
	function getCurrentTemp() {
		$command = escapeshellcmd($this->externalCommandTemp);
		$output = shell_exec($command);
		
		if (!isset($output)){
			$output = 'undefined';
		}
		
		return $output;
	}
	
	function launchPoeleScript(){
		$command = escapeshellcmd($this->externalCommandMcz);
		$output = shell_exec($command);
		
		return $output;
	}

}

?>