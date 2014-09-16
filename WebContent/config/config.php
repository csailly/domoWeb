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
	if (PHP_OS == "Linux"){
		//$externalCommandTemp = '/home/pi/scripts/capteurs/tmp102/tmp102.py';
		$externalCommandTemp = 'python /home/pi/scripts/domoCore/com/nestof/domocore/service/TempService.py';
		$externalCommandMcz = 'sudo python /home/pi/scripts/domoCore/run.py  >> /home/pi/log/domocore.log 2>&1';
	}else{
		//$externalCommandTemp = 'C:\Python34\python.exe D:\Documents\Work\domocore\com\nestof\domocore\service\TempServiceDev.py';
		//$externalCommandMcz = 'C:\Python34\python.exe D:\Documents\Work\domocore\run.py';
		
		$externalCommandTemp = 'D:\+sandbox\python3.4.0\python.exe D:\+sandbox\work\domocore\com\nestof\domocore\service\TempServiceDev.py';
		$externalCommandMcz = 'D:\+sandbox\python3.4.0\python.exe D:\+sandbox\work\domocore\run.py';
	}
	
	
	
?>
