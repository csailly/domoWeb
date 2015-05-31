<?php
class DomoWebConfig {
	private $databaseDirectory;
	private $databaseFilename;

	public $databaseConnexion;
	public $externalCommandTemp;
	public $externalCommandMcz;
	public $externalCommandUpdateDomoWeb;
	public $externalCommandUpdateDomoCore;


	function __construct(){
		/* Configuration de la base de données */
		if (PHP_OS == "Linux"){
			$this->databaseDirectory = '/home/pi/syno/';
			$this->databaseFilename = 'domotique.sqlite';
		}else{
			$this->databaseDirectory = dirname(__FILE__).'/../';
			$this->databaseFilename = 'domotique.sqlite';
		}

		try	{
			$this->databaseConnexion = new PDO('sqlite:'.$this->databaseDirectory.$this->databaseFilename);
			$this->databaseConnexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}catch(Exception $e){
			echo 'Configuration de la base de donnée <br/>';
			echo 'Erreur : '.$e->getMessage().'<br />';
			echo 'N° : '.$e->getCode();
			die();
		}

		/* Les commandes externes */
		if (PHP_OS == "Linux"){
			$this->externalCommandTemp = 'python /home/pi/apps/domoCore/com/nestof/domocore/service/TempService.py';
			$this->externalCommandMcz = 'sudo python /home/pi/apps/domoCore/run.py';
			$this->externalCommandUpdateDomoWeb = 'sh '.__DIR__.'/update.sh';
			$this->externalCommandUpdateDomoCore = 'sudo sh /home/pi/apps/domoCore/conf/update.sh';
		}else{
			$this->externalCommandTemp = 'C:\Python34\python.exe D:\Documents\Work\domocore\com\nestof\domocore\service\TempServiceDev.py';
			$this->externalCommandMcz = 'C:\Python34\python.exe D:\Documents\Work\domocore\run.py';

			//$this->externalCommandTemp = 'D:\+sandbox\python3.4.0\python.exe D:\+sandbox\work\domocore\com\nestof\domocore\service\TempServiceDev.py';
			//$this->externalCommandMcz = 'D:\+sandbox\python3.4.0\python.exe D:\+sandbox\work\domocore\run.py';
			$this->externalCommandUpdateDomoWeb = __DIR__.'\updateWebApp.sh';
			$this->externalCommandUpdateDomoCore = 'sudo python /home/pi/scripts/domoCore/conf/update.sh';
		}
	}
}
?>
