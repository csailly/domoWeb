<?php
	/* Configuration de la base de données */

	

	if (PHP_OS == "Linux"){
		$databaseDirectory = '/home/pi/syno/';
		$databaseFilename = 'domotique.sqlite';
	}else{
		$databaseDirectory = dirname(__FILE__).'/../';
		$databaseFilename = 'domotique.sqlite';
	}
	
	
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
	$externalCommandTemp = '/home/pi/scripts/capteurs/tmp102/tmp102.py';
	$externalCommandMcz = 'sudo python /home/pi/scripts/domoCore/run.py  >> /home/pi/log/domocore.log 2>&1';
			

	
?>