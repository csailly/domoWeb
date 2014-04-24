<?php
include_once $_SERVER ['DOCUMENT_ROOT'] . '/config/config.php';
class ExternalService {	
	
	function __construct() {
		
	}
	
		
	function getCurrentTemp() {
		$command = escapeshellcmd($externalTemp);
		$output = shell_exec($command);
		
		return $output;
	}

}

?>