<?php
	/* Configuration de la base de données */

	$databaseDirectory = dirname(__FILE__).'/../';
	$databaseFilename = 'domotique.sqlite';

	//$databaseDirectory = '/home/pi/syno/';
	//$databaseFilename = 'domotique.sqlite';
	
	
	
	try	{		
		$databaseConnexion = new PDO('sqlite:'.$databaseDirectory.$databaseFilename);
		$databaseConnexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}catch(Exception $e){
		echo $databaseDirectory.''.$databaseFilename;
		echo 'Erreur : '.$e->getMessage().'<br />';
		echo 'N° : '.$e->getCode();
		die();
	}
	
	/* Les commandes externes */
	$externalTemp = '/home/pi/scripts/capteurs/tmp102/tmp102.py';
	$externalMcz = '/home/pi/main.py';
			

	
?>