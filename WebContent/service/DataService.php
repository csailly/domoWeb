<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/dao/PeriodeDAO.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/dao/ModeDAO.php';

class DataService {

	private $periodeDao;

	private $modeDao;

	function __construct($connexion){
		$this->periodeDao = new PeriodeDAO($connexion);
		$this->modeDao = new ModeDAO($connexion);
	}

	/**
		* Retourne la liste des périodes.
		*/
	function getPeriodesList() {
		return $this->periodeDao->getPeriodesList();
	}

	/**
		*	Supprime une période.
		*	@param periodId : identifiant de la période à supprimer.
		*/
	function deletePeriode($periodId){
		return $this->periodeDao->deletePeriode($periodId);
	}

	/**
		* 	Recherche un mode.
		*	@param modeId : identifiant du mode.
		*/
	function getModeById($modeId) {
		return $this->modeDao->getModeById($modeId);
	}

	/**
		*	Crée une nouvelle période.
		*	@param day		:	le jour concerné par la période
		*	@param startDate	:	la date de début de la période (incluse)
		*	@param endDate	:	la date de fin de la période (incluse)
		*	@param startHour	:	l'heure de début de la période (incluse)
		*	@param endHour	:	l'heure de fin de la période (incluse)
		*	@param modeId		:	l'identifiant du mode de chauffe de la période
		*
		*/
	function createPeriode($day, $startDate, $endDate, $startHour, $endHour, $modeId){
		return $this->periodeDao->createPeriode($day, $startDate, $endDate, $startHour, $endHour, $modeId);
	}

}


?>