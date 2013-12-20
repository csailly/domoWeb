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
		$response = array (
				'result' => 'success' 
		);
		
		$queryString = "DELETE FROM periode WHERE id = :periodId";
		try {
			$stmt = $this->connexion->prepare ( $queryString );
			$stmt->execute ( array (
					'periodId' => $periodId 
			) );
		} catch ( PDOException $e ) {
			$response ['result'] = 'error';
			$errorsMsgs = array ();
			$errorsMsgs ["exception"] = $e->getMessage ();
			$errors = array (
					'errorsMsgs' => $errorsMsgs
			);
			$response ['errors'] = $errors;
		}
		
		return json_encode ( $response );
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
		$response = $this->checkPeriodValues ( $day, $startDate, $endDate, $startHour, $endHour, $modeId );
		
		if ($response ['result'] === 'error') {
			die ( json_encode ( $response ) );
		}
		
		$_startDate = CalendarUtils::transformDate1($startDate);
		$_endDate = CalendarUtils::transformDate1($endDate);
		$_startHour = CalendarUtils::transformHour($startHour);
		$_endHour = CalendarUtils::transformHour($endHour);
		
		// Execute the query
		$queryString = "INSERT INTO periode(jour, dateDebut, dateFin, heureDebut, heureFin, modeId) VALUES ( :jour, :dateDebut, :dateFin, :heureDebut, :heureFin, :modeId)";
		try {
			$stmt = $this->connexion->prepare ( $queryString );
			$stmt->execute ( array (
					'jour' => $day,
					'dateDebut' => $_startDate,
					'dateFin' => $_endDate,
					'heureDebut' => $_startHour,
					'heureFin' => $_endHour,
					'modeId' => $modeId 
			) );
		} catch ( PDOException $e ) {
			$response ['result'] = 'error';
			$errorsMsgs = array ();
			$errorsMsgs ["exception"] = $e->getMessage ();
			$errors = array (
					'errorsMsgs' => $errorsMsgs 
			);
			$response ['errors'] = $errors;
		}
		
		return json_encode ( $response );
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
		$response = $this->checkPeriodValues ( $day, $startDate, $endDate, $startHour, $endHour, $modeId );
		
		if ($response ['result'] === 'error') {
			die ( json_encode ( $response ) );
		}
		
		$_startDate = CalendarUtils::transformDate1($startDate);
		$_endDate = CalendarUtils::transformDate1($endDate);
		$_startHour = CalendarUtils::transformHour($startHour);
		$_endHour = CalendarUtils::transformHour($endHour);
		
		// Execute the query
		$queryString = "UPDATE periode SET jour = :jour, dateDebut = :dateDebut, dateFin = :dateFin, heureDebut = :heureDebut, heureFin = :heureFin, modeId = :modeId WHERE id = :periodId";
		try {
			$stmt = $this->connexion->prepare ( $queryString );
			$stmt->execute ( array (
					'jour' => $day,
					'dateDebut' => $_startDate,
					'dateFin' => $_endDate,
					'heureDebut' => $_startHour,
					'heureFin' => $_endHour,
					'modeId' => $modeId,
					'periodId' => $periodId 
			) );
		} catch ( PDOException $e ) {
			$response ['result'] = 'error';
			$errorsMsgs = array ();
			$errorsMsgs ["exception"] = $e->getMessage ();
			$errors = array (
					'errorsMsgs' => $errorsMsgs 
			);
			$response ['errors'] = $errors;
		}
		
		return json_encode ( $response );
	}
	
	/**
	 * Teste la validité des données de la période.
	 * 
	 * @param
	 *        	day			:	le jour concerné par la période
	 * @param
	 *        	startDate	:	la date de début de la période (incluse) Format : DD/MM/YYYY
	 * @param
	 *        	endDate		:	la date de fin de la période (incluse) Format : DD/MM/YYYY
	 * @param
	 *        	startHour	:	l'heure de début de la période (incluse) Format : (H)H:MM
	 * @param
	 *        	endHour		:	l'heure de fin de la période (incluse) Format : (H)H:MM
	 * @param
	 *        	modeId		:	l'identifiant du mode de chauffe de la période.
	 *        	
	 */
	function checkPeriodValues($day, $startDate, $endDate, $startHour, $endHour, $modeId) {
		$response = array (
				'result' => 'error' 
		);
		
		$errorsMsgs = array ();
		// Check mandatory fields
		if ($day < 1) {
			if (strlen ( $startDate ) == 0) {
				$errorsMsgs ["startDate"] = "Field start date required";
			}
			if (strlen ( $endDate ) == 0) {
				$errorsMsgs ["endDate"] = "Field endDate required";
			}
			if (count ( $errorsMsgs ) > 0) {
				$errorsMsgs ["day"] = "Field day required";
			}
		}
		if (strlen ( $startHour ) == 0) {
			$errorsMsgs ["startHour"] = "Field startHour required";
		}
		if (strlen ( $endHour ) == 0) {
			$errorsMsgs ["endHour"] = "Field endHour required";
		}
		if ($modeId <= 0) {
			$errorsMsgs ["mode"] = "Field mode required";
		}
		
		if (count ( $errorsMsgs ) > 0) {
			$errors = array (
					'errorsMsgs' => $errorsMsgs 
			);
			$response ['errors'] = $errors;
			return $response;
		}
		
		// Check formats
		$checkFormatStartDate = true;
		$checkFormatEndDate = true;
		$checkFormatStartHour = true;
		$checkFormatEndHour = true;
		
		if ($day < 1) {
			$checkFormatStartDate = CalendarUtils::checkDate ( $startDate );
			$checkFormatEndDate = CalendarUtils::checkDate ( $endDate );
		}
		
		$checkFormatStartHour = CalendarUtils::checkHour ( $startHour );
		$checkFormatEndHour = CalendarUtils::checkHour ( $startHour );
		
		if (! $checkFormatStartDate) {
			$errorsMsgs ["startDate"] = "Invalid format for start date";
		}
		if (! $checkFormatEndDate) {
			$errorsMsgs ["endDate"] = "Invalid format for end date";
		}
		if (! $checkFormatStartHour) {
			$errorsMsgs ["startHour"] = "Invalid format for start hour";
		}
		if (! $checkFormatEndHour) {
			$errorsMsgs ["endHour"] = "Invalid format for end hour";
		}
		
		if (count ( $errorsMsgs ) > 0) {
			$errors = array (
					'errorsMsgs' => $errorsMsgs 
			);
			$response ['errors'] = $errors;
			return $response;
		}
		
		$_startDate = CalendarUtils::transformDate1($startDate);
		$_endDate = CalendarUtils::transformDate1($endDate);
		$_startHour = CalendarUtils::transformHour($startHour);
		$_endHour = CalendarUtils::transformHour($endHour);
		
		
		// Check rules
		date_default_timezone_set ( 'Europe/Paris' );
		
		// Dates rules
		$rulesDates = true;
		$datetime1 = new DateTime ( $_startDate );
		$datetime2 = new DateTime ( $_endDate );
		$interval = $datetime1->diff ( $datetime2 );
		$res = $interval->format ( '%r%a' );
		if ($res < 0) {
			$rulesDates = false;
		}
		if (! $rulesDates) {
			$errorsMsgs ["startDate"] = "Start date must be prior to end date";
			$errorsMsgs ["endDate"] = "";
		}
		if (count ( $errorsMsgs ) > 0) {
			$errors = array (
					'errorsMsgs' => $errorsMsgs 
			);
			$response ['errors'] = $errors;
			return $response;
		}
		
		// Hours rules
		$rulesHour = true;
		$datetime1 = new DateTime ( $_startHour );
		$datetime2 = new DateTime ( $_endHour );
		$interval = $datetime1->diff ( $datetime2 );
		$res = $interval->format ( '%r%i' );
		if ($res < 0) {
			$rulesHour = false;
		}
		if (! $rulesHour) {
			$errorsMsgs ["startHour"] = "Start hour must be prior to end hour";
			$errorsMsgs ["endHour"] = "";
		}
		if (count ( $errorsMsgs ) > 0) {
			$errors = array (
					'errorsMsgs' => $errorsMsgs 
			);
			$response ['errors'] = $errors;
			return $response;
		}
		
		$response ['result'] = 'success';
		return $response;
	}

}
?>