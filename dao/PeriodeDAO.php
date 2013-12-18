<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/dao/GenericDAO.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/bo/Periode.php';

class PeriodeDAO extends GenericDAO {

	/**
	 * Retourne la liste des périodes.
	 * @return multitype:Periode
	 */
	function getPeriodesList() {
		//TODO renvoyer un flux json
		$periodes = array();

		$resultats=$this->connexion->query("SELECT * FROM periode p order by p.jour,p.heureDebut, p.dateDebut"); // on va chercher tous les membres de la table qu'on trie par ordre croissant
		$resultats->setFetchMode(PDO::FETCH_OBJ); // on dit qu'on veut que le résultat soit récupérable sous forme d'objet
		while( $ligne = $resultats->fetch()) // on récupère la liste des membres
		{
			$periode = new Periode($ligne->id,$ligne->jour,$ligne->dateDebut,$ligne->dateFin,$ligne->heureDebut,$ligne->heureFin,$ligne->modeId);
			$periodes[] = $periode;
		}
		$resultats->closeCursor(); // on ferme le curseur des résultats

		return $periodes;
	}

	/**
		*	Supprime une période.
		*	@param periodId : identifiant de la période à supprimer.
		*/
	function deletePeriode($periodId){
		$response = array('result' => 'error');

		$queryString = "DELETE FROM periode WHERE id = :periodId";
		$stmt=$this->connexion->prepare($queryString);
		$stmt->execute(array( 'periodId' => $periodId));
			
		$response['result'] = 'success';
		return json_encode($response);
	}

	/**
		*	Crée une nouvelle période.
		*	@param day		:	le jour concerné par la période
		*	@param startDate	:	la date de début de la période (incluse) Format : DD/MM/YYYY
		*	@param endDate	:	la date de fin de la période (incluse) Format : DD/MM/YYYY
		*	@param startHour	:	l'heure de début de la période (incluse) Format : (H)H:MM
		*	@param endHour	:	l'heure de fin de la période (incluse) Format : (H)H:MM
		*	@param modeId		:	l'identifiant du mode de chauffe de la période.
		*
		*/
	function createPeriode($day, $startDate, $endDate, $startHour, $endHour, $modeId){
		$response = array('result' => 'error');

		$errorsMsgs = array();
		//Check mandatory fields
		if ($day < 1){
			if (strlen($startDate) == 0){
				$errorsMsgs["startDate"] = "Field start date required";
			}
			if (strlen($endDate) == 0){
				$errorsMsgs["endDate"] = "Field endDate required";
			}
			if (count($errorsMsgs) > 0) {
				$errorsMsgs["day"] = "Field day required";
			}
		}
		if (strlen($startHour) == 0){
			$errorsMsgs["startHour"] = "Field startHour required";
		}
		if (strlen($endHour) == 0){
			$errorsMsgs["endHour"] = "Field endHour required";
		}
		if ($modeId <= 0){
			$errorsMsgs["mode"] = "Field mode required";
		}
			
			
		if (count($errorsMsgs) > 0){
			$errors = array('errorsMsgs'=>$errorsMsgs);
			$response['result'] = 'error';
			$response['errors'] = $errors;
			die(json_encode($response));
		}

		//Check formats
		$checkFormatStartDate = true;
		$checkFormatEndDate = true;
		$checkFormatStartHour = true;
		$checkFormatEndHour = true;
			
		if ($day < 1){
			$checkFormatStartDate = $this->checkDate($startDate);
			$checkFormatEndDate = $this->checkDate($endDate);
		}
			
		$checkFormatStartHour = $this->checkHour($startHour);
		$checkFormatEndHour = $this->checkHour($startHour);
			
			
		if (!$checkFormatStartDate){
			$errorsMsgs["startDate"] = "Invalid format for start date";
		}
		if (!$checkFormatEndDate){
			$errorsMsgs["endDate"] = "Invalid format for end date";
		}
		if (!$checkFormatStartHour){
			$errorsMsgs["startHour"] = "Invalid format for start hour";
		}
		if (!$checkFormatEndHour){
			$errorsMsgs["endHour"] = "Invalid format for end hour";
		}
			
		if (count($errorsMsgs) > 0){
			$errors = array('errorsMsgs'=>$errorsMsgs);
			$response['result'] = 'error';
			$response['errors'] = $errors;
			die(json_encode($response));
		}
			
			
			
		$_startDate = null;
		$_endDate = null;
		$_startHour = null;
		$_endHour = null;
			
		//Transform start date from DD/MM/YYYY to YYYY-MM-DD
		if (strlen($startDate) > 0){
			$tmp = explode("/", $startDate);
			$_startDate = $tmp[2]."-".$tmp[1]."-".$tmp[0];
		}
			
		//Transform end date from DD/MM/YYYY to YYYY-MM-DD
		if (strlen($endDate) > 0){
			$tmp = explode("/", $endDate);
			$_endDate = $tmp[2]."-".$tmp[1]."-".$tmp[0];
		}
			
		//Transform start hour from H:MM to HH:MM
		$tmp = explode(":", $startHour);
		if (strlen($tmp[0]) == 1){
			$_startHour = "0".$startHour;
		}else{
			$_startHour = $startHour;
		}
			
		//Transform end hour from H:MM to HH:MM
		$tmp = explode(":", $endHour);
		if (strlen($tmp[0]) == 1){
			$_endHour = "0".$endHour;
		}else{
			$_endHour = $endHour;
		}

		//Check rules
		date_default_timezone_set('Europe/Paris');
			
		//Dates rules
		$rulesDates = true;
		$datetime1 = new DateTime($_startDate);
		$datetime2 = new DateTime($_endDate);
		$interval = $datetime1->diff($datetime2);
		$res = $interval->format('%r%a');
		if ($res < 0 ){
			$rulesDates = false;
		}
		if (!$rulesDates){
			die("Dates invalides");
		}
			
		//Hours rules
		$rulesHour = true;
		$datetime1 = new DateTime($_startHour);
		$datetime2 = new DateTime($_endHour);
		$interval = $datetime1->diff($datetime2);
		$res = $interval->format('%r%i');
		if ($res < 0 ){
			$rulesHour = false;
		}
		if (!$rulesHour){
			die("Dates invalides");
		}
			
		//Execute the query
		$queryString = "INSERT INTO periode(jour, dateDebut, dateFin, heureDebut, heureFin, modeId) VALUES ( :jour, :dateDebut, :dateFin, :heureDebut, :heureFin, :modeId)";
		$stmt=$this->connexion->prepare($queryString);
		$stmt->execute(array( 'jour' => $day, 'dateDebut' => $_startDate, 'dateFin' => $_endDate, 'heureDebut' => $_startHour, 'heureFin' => $_endHour, 'modeId' => $modeId ));

		$response['result'] = 'success';
		return json_encode($response);
	}

	/**
	 * Check the date.
	 * 	@param  $date
	 *
	 *	@return true if date is empty or is valid.
	 *
	 */
	function checkDate($date){
		if (strlen($date) > 0){
			$res = date_parse_from_format("d/m/Y", $date);
			if ($res['error_count'] > 0 || $res['warning_count'] > 0){
				return false;
			}else if (!checkdate ( explode("/", $date)[1] , explode("/", $date)[0] , explode("/", $date)[2] )){
				return false;
			}
			return true;
		}else{
			return false;
		}
	}

	/**
		*	Check the hour.
		*	@return true if date is empty or is valid.
		**/
	function checkHour($hour){
		if (strlen($hour) > 0){
			$res = date_parse_from_format("H:i", $hour);
			if ($res['error_count'] > 0 || $res['warning_count'] > 0){
				return false;
			}
			return true;
		}else{
			return false;
		}
	}

}
?>