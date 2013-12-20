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
	function getAllPeriodes() {
		return $this->periodeDao->getAllPeriodes();
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
	 * Renvoie la liste des Modes
	 * @return multitype:Mode
	 */
	function getAllModes(){
		return $this->modeDao->getAllModes();
	}

	/**
		*	Crée une nouvelle période.
		*	@param day		:	le jour concerné par la période.
		*	@param startDate	:	la date de début de la période (incluse).
		*	@param endDate	:	la date de fin de la période (incluse).
		*	@param startHour	:	l'heure de début de la période (incluse).
		*	@param endHour	:	l'heure de fin de la période (incluse).
		*	@param modeId		:	l'identifiant du mode de chauffe de la période.
		*
		*/
	function createPeriode($day, $startDate, $endDate, $startHour, $endHour, $modeId){
		return $this->periodeDao->createPeriode($day, $startDate, $endDate, $startHour, $endHour, $modeId);
	}
	
	/**
	 *	Met à jour une période.
	 *	@param periodId	:	l'identifiant de la période.
	 *	@param day		:	le jour concerné par la période.
	 *	@param startDate	:	la date de début de la période (incluse).
	 *	@param endDate	:	la date de fin de la période (incluse).
	 *	@param startHour	:	l'heure de début de la période (incluse).
	 *	@param endHour	:	l'heure de fin de la période (incluse).
	 *	@param modeId		:	l'identifiant du mode de chauffe de la période.
	 *
	 */
	function updatePeriode($periodId, $day, $startDate, $endDate, $startHour, $endHour, $modeId){
		return $this->periodeDao->updatePeriode($periodId, $day, $startDate, $endDate, $startHour, $endHour, $modeId);
	}
	
	/**
	 * Crée un nouveau mode
	 * @param  label	:	le libellé du mode
	 * @param  cons	:	la température de consigne
	 * @param  max		:	la température maximale
	 */
	function createMode($label, $cons, $max){
		return $this->modeDao->createMode($label, $cons, $max);
	}
	
	/**
	 * Met à jour le mode.
	 * @param  modeId	:	l'identifiant du mode à mettre à jour
	 * @param  label	:	le libellé du mode
	 * @param  cons	:	la température de consigne
	 * @param  max		:	la température maximale
	 */
	function updateMode($modeId, $label, $cons, $max){
		return $this->modeDao->updateMode($modeId, $label, $cons, $max);
	}
	
	/**
	 * Supprime un mode.
	 *
	 * @param modeId : l'identifiant du mode à supprimer.
	 */
	function delete($modeId){
		return $this->modeDao->delete($modeId);
	}

}


?>