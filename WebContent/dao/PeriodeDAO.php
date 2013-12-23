<?php
include_once $_SERVER ['DOCUMENT_ROOT'] . '/dao/GenericDAO.php';
include_once $_SERVER ['DOCUMENT_ROOT'] . '/bo/Periode.php';
include_once $_SERVER ['DOCUMENT_ROOT'] . '/utils/CalendarUtils.php';
class PeriodeDAO extends GenericDAO {
	
	/**
	 * Retourne la liste des périodes.
	 *
	 * @return multitype:Periode
	 */
	function getAllPeriodes() {
		// TODO renvoyer un flux json
		$periodes = array ();
		
		$resultats = $this->connexion->query ( "SELECT * FROM periode p order by p.jour,p.heureDebut, p.dateDebut" ); // on va chercher tous les membres de la table qu'on trie par ordre croissant
		$resultats->setFetchMode ( PDO::FETCH_OBJ ); // on dit qu'on veut que le résultat soit récupérable sous forme d'objet
		while ( $ligne = $resultats->fetch () ) 		// on récupère la liste des membres
		{
			$periode = new Periode ( $ligne->id, $ligne->jour, $ligne->dateDebut, $ligne->dateFin, $ligne->heureDebut, $ligne->heureFin, $ligne->modeId );
			$periodes [] = $periode;
		}
		$resultats->closeCursor (); // on ferme le curseur des résultats
		
		return $periodes;
	}
	
	/**
	 * Supprime une période.
	 *
	 * @param
	 *        	periodId : identifiant de la période à supprimer.
	 */
	function deletePeriode($periodId) {
		$queryString = "DELETE FROM periode WHERE id = :periodId";
		
		$stmt = $this->connexion->prepare ( $queryString );
		$stmt->execute ( array (
				'periodId' => $periodId 
		) );
	}
	
	/**
	 * Crée une nouvelle période.
	 *
	 * @param
	 *        	day		:	le jour concerné par la période
	 * @param
	 *        	startDate	:	la date de début de la période (incluse) Format : DD/MM/YYYY
	 * @param
	 *        	endDate	:	la date de fin de la période (incluse) Format : DD/MM/YYYY
	 * @param
	 *        	startHour	:	l'heure de début de la période (incluse) Format : (H)H:MM
	 * @param
	 *        	endHour	:	l'heure de fin de la période (incluse) Format : (H)H:MM
	 * @param
	 *        	modeId		:	l'identifiant du mode de chauffe de la période.
	 *        	
	 */
	function createPeriode($day, $startDate, $endDate, $startHour, $endHour, $modeId) {
		// Execute the query
		$queryString = "INSERT INTO periode(jour, dateDebut, dateFin, heureDebut, heureFin, modeId) VALUES ( :jour, :dateDebut, :dateFin, :heureDebut, :heureFin, :modeId)";
		
		$stmt = $this->connexion->prepare ( $queryString );
		$stmt->execute ( array (
				'jour' => $day,
				'dateDebut' => $startDate,
				'dateFin' => $endDate,
				'heureDebut' => $startHour,
				'heureFin' => $endHour,
				'modeId' => $modeId 
		) );
	}
	
	/**
	 * Met à jour une période.
	 *
	 * @param
	 *        	periodId	: l'identifiant de la période à mettre à jour.
	 * @param
	 *        	day		:	le jour concerné par la période
	 * @param
	 *        	startDate	:	la date de début de la période (incluse) Format : DD/MM/YYYY
	 * @param
	 *        	endDate	:	la date de fin de la période (incluse) Format : DD/MM/YYYY
	 * @param
	 *        	startHour	:	l'heure de début de la période (incluse) Format : (H)H:MM
	 * @param
	 *        	endHour	:	l'heure de fin de la période (incluse) Format : (H)H:MM
	 * @param
	 *        	modeId		:	l'identifiant du mode de chauffe de la période.
	 *        	
	 */
	function updatePeriode($periodId, $day, $startDate, $endDate, $startHour, $endHour, $modeId) {		
		// Execute the query
		$queryString = "UPDATE periode SET jour = :jour, dateDebut = :dateDebut, dateFin = :dateFin, heureDebut = :heureDebut, heureFin = :heureFin, modeId = :modeId WHERE id = :periodId";
		
		$stmt = $this->connexion->prepare ( $queryString );
		$stmt->execute ( array (
				'jour' => $day,
				'dateDebut' => $startDate,
				'dateFin' => $endDate,
				'heureDebut' => $startHour,
				'heureFin' => $endHour,
				'modeId' => $modeId,
				'periodId' => $periodId 
		) );
	}
}
?>