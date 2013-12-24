<?php
include_once $_SERVER ['DOCUMENT_ROOT'] . '/utils/CalendarUtils.php';
include_once $_SERVER ['DOCUMENT_ROOT'] . '/dao/PeriodeDAO.php';
include_once $_SERVER ['DOCUMENT_ROOT'] . '/dao/ModeDAO.php';
include_once $_SERVER ['DOCUMENT_ROOT'] . '/dao/TemperatureDAO.php';
class DataService {
	private $periodeDao;
	private $modeDao;
	private $temperatureDao;
	function __construct($connexion) {
		$this->periodeDao = new PeriodeDAO ( $connexion );
		$this->modeDao = new ModeDAO ( $connexion );
		$this->temperatureDao = new TemperatureDAO($connexion);
	}
	
	/**
	 * Retourne la liste des périodes.
	 */
	function getAllPeriodes() {
		// TODO Renvoyer json
		$response = array (
				'result' => 'success' 
		);
		try {
			return $this->periodeDao->getAllPeriodes ();
		} catch ( PDOException $e ) {
			$response ['result'] = 'error';
			$errorsMsgs = array ();
			$errorsMsgs ["exception"] = $e->getMessage ();
			$errors = array (
					'errorsMsgs' => $errorsMsgs 
			);
			$response ['errors'] = $errors;
		}
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
		try {
			$this->periodeDao->deletePeriode ( $periodId );
		} catch ( PDOException $e ) {
			$response ['result'] = 'error';
			$errorsMsgs = array ();
			$errorsMsgs ["exception"] = $e->getMessage ();
			$errors = array (
					'errorsMsgs' => $errorsMsgs 
			);
			$response ['errors'] = $errors;
		}
		
		return $response;
	}
	
	/**
	 * Recherche un mode.
	 *
	 * @param
	 *        	modeId : identifiant du mode.
	 */
	function getModeById($modeId) {
		// TODO Renvoyer json
		$response = array (
				'result' => 'success' 
		);
		try {
			return $this->modeDao->getModeById ( $modeId );
		} catch ( PDOException $e ) {
			$response ['result'] = 'error';
			$errorsMsgs = array ();
			$errorsMsgs ["exception"] = $e->getMessage ();
			$errors = array (
					'errorsMsgs' => $errorsMsgs 
			);
			$response ['errors'] = $errors;
		}
	}
	
	/**
	 * Renvoie la liste des Modes
	 *
	 * @return multitype:Mode
	 */
	function getAllModes() {
		// TODO Renvoyer json
		$response = array (
				'result' => 'success' 
		);
		try {
			return $this->modeDao->getAllModes ();
		} catch ( PDOException $e ) {
			$response ['result'] = 'error';
			$errorsMsgs = array ();
			$errorsMsgs ["exception"] = $e->getMessage ();
			$errors = array (
					'errorsMsgs' => $errorsMsgs 
			);
			$response ['errors'] = $errors;
		}
	}
	
	/**
	 * Crée une nouvelle période.
	 *
	 * @param
	 *        	day		:	le jour concerné par la période.
	 * @param
	 *        	startDate	:	la date de début de la période (incluse).
	 * @param
	 *        	endDate	:	la date de fin de la période (incluse).
	 * @param
	 *        	startHour	:	l'heure de début de la période (incluse).
	 * @param
	 *        	endHour	:	l'heure de fin de la période (incluse).
	 * @param
	 *        	modeId		:	l'identifiant du mode de chauffe de la période.
	 *        	
	 */
	function createPeriode($day, $startDate, $endDate, $startHour, $endHour, $modeId) {
		$response = array (
				'result' => 'success' 
		);
		
		$response = $this->checkPeriodValues ( $day, $startDate, $endDate, $startHour, $endHour, $modeId );
		
		if ($response ['result'] === 'error') {
			return $response;
		}
		
		$_startDate = CalendarUtils::transformDate1 ( $startDate );
		$_endDate = CalendarUtils::transformDate1 ( $endDate );
		$_startHour = CalendarUtils::transformHour ( $startHour );
		$_endHour = CalendarUtils::transformHour ( $endHour );
		
		try {
			$this->periodeDao->createPeriode ( $day, $_startDate, $_endDate, $_startHour, $_endHour, $modeId );
		} catch ( PDOException $e ) {
			$response ['result'] = 'error';
			$errorsMsgs = array ();
			$errorsMsgs ["exception"] = $e->getMessage ();
			$errors = array (
					'errorsMsgs' => $errorsMsgs 
			);
			$response ['errors'] = $errors;
		} catch ( Exception $e ) {
			$response ['result'] = 'error';
			$errorsMsgs = array ();
			$errorsMsgs ["exception"] = $e->getMessage ();
			$errors = array (
					'errorsMsgs' => $errorsMsgs 
			);
			$response ['errors'] = $errors;
		}
		
		return $response;
	}
	
	/**
	 * Met à jour une période.
	 *
	 * @param
	 *        	periodId	:	l'identifiant de la période.
	 * @param
	 *        	day		:	le jour concerné par la période.
	 * @param
	 *        	startDate	:	la date de début de la période (incluse).
	 * @param
	 *        	endDate	:	la date de fin de la période (incluse).
	 * @param
	 *        	startHour	:	l'heure de début de la période (incluse).
	 * @param
	 *        	endHour	:	l'heure de fin de la période (incluse).
	 * @param
	 *        	modeId		:	l'identifiant du mode de chauffe de la période.
	 *        	
	 */
	function updatePeriode($periodId, $day, $startDate, $endDate, $startHour, $endHour, $modeId) {
		$response = $this->checkPeriodValues ( $day, $startDate, $endDate, $startHour, $endHour, $modeId );
		
		if ($response ['result'] === 'error') {
			die ( json_encode ( $response ) );
		}
		
		$_startDate = CalendarUtils::transformDate1 ( $startDate );
		$_endDate = CalendarUtils::transformDate1 ( $endDate );
		$_startHour = CalendarUtils::transformHour ( $startHour );
		$_endHour = CalendarUtils::transformHour ( $endHour );
		
		try {
			$this->periodeDao->updatePeriode ( $periodId, $day, $_startDate, $_endDate, $_startHour, $_endHour, $modeId );
		} catch ( PDOException $e ) {
			$response ['result'] = 'error';
			$errorsMsgs = array ();
			$errorsMsgs ["exception"] = $e->getMessage ();
			$errors = array (
					'errorsMsgs' => $errorsMsgs 
			);
			$response ['errors'] = $errors;
		}
		
		return $response;
	}
	
	/**
	 * Crée un nouveau mode
	 *
	 * @param
	 *        	label	:	le libellé du mode
	 * @param
	 *        	cons	:	la température de consigne
	 * @param
	 *        	max		:	la température maximale
	 */
	function createMode($label, $cons, $max) {
		// TODO check parameters
		$response = array (
				'result' => 'success' 
		);
		try {
			return $this->modeDao->createMode ( $label, $cons, $max );
		} catch ( PDOException $e ) {
			$response ['result'] = 'error';
			$errorsMsgs = array ();
			$errorsMsgs ["exception"] = $e->getMessage ();
			$errors = array (
					'errorsMsgs' => $errorsMsgs 
			);
			$response ['errors'] = $errors;
		}
		
		$response;
	}
	
	/**
	 * Met à jour le mode.
	 *
	 * @param
	 *        	modeId	:	l'identifiant du mode à mettre à jour
	 * @param
	 *        	label	:	le libellé du mode
	 * @param
	 *        	cons	:	la température de consigne
	 * @param
	 *        	max		:	la température maximale
	 */
	function updateMode($modeId, $label, $cons, $max) {
		// TODO check parameters
		$response = array (
				'result' => 'success' 
		);
		try {
			return $this->modeDao->updateMode ( $modeId, $label, $cons, $max );
		} catch ( PDOException $e ) {
			$response ['result'] = 'error';
			$errorsMsgs = array ();
			$errorsMsgs ["exception"] = $e->getMessage ();
			$errors = array (
					'errorsMsgs' => $errorsMsgs 
			);
			$response ['errors'] = $errors;
		}
		return $response;
	}
	
	/**
	 * Supprime un mode.
	 *
	 * @param
	 *        	modeId : l'identifiant du mode à supprimer.
	 */
	function delete($modeId) {
		$response = array (
				'result' => 'success' 
		);
		try {
			return $this->modeDao->delete ( $modeId );
		} catch ( PDOException $e ) {
			$response ['result'] = 'error';
			$errorsMsgs = array ();
			$errorsMsgs ["exception"] = $e->getMessage ();
			$errors = array (
					'errorsMsgs' => $errorsMsgs 
			);
			$response ['errors'] = $errors;
		}
		
		return $response;
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
				'result' => 'success' 
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
			$response ['result'] = 'error';
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
			$response ['result'] = 'error';
			$errors = array (
					'errorsMsgs' => $errorsMsgs 
			);
			$response ['errors'] = $errors;
			return $response;
		}
		
		$_startDate = CalendarUtils::transformDate1 ( $startDate );
		$_endDate = CalendarUtils::transformDate1 ( $endDate );
		$_startHour = CalendarUtils::transformHour ( $startHour );
		$_endHour = CalendarUtils::transformHour ( $endHour );
		
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
			$response ['result'] = 'error';
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
			$response ['result'] = 'error';
			$errors = array (
					'errorsMsgs' => $errorsMsgs 
			);
			$response ['errors'] = $errors;
			return $response;
		}
		
		return $response;
	}
	
	function getAllTemperatures(){
		return $this->temperatureDao->getAllTemperatures();			
	}
}

?>