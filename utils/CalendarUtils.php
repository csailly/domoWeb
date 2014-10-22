<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/utils/Enum.php';

class CalendarUtils {
	


	/**
	 * Transform a date from DD/MM/YYYY to YYYY-MM-DD
	 *
	 * @param $date
	 */
	public static function transformDate1($date) {
		$res = null;
		if (strlen ( $date ) > 0) {
			$tmp = explode ( "/", $date );
			$res = $tmp [2] . "-" . $tmp [1] . "-" . $tmp [0];
		}
		return $res;
	}
	
	/**
	 * Transform a date from YYYY-MM-DD to DD/MM/YYYY 
	 *
	 * @param $date
	 */
	public static function transformDate2($date) {
		$res = null;
		if (strlen ( $date ) > 0) {
			$tmp = explode ( "-", $date );
			$res = $tmp [2] . "/" . $tmp [1] . "/" . $tmp [0];
		}
		return $res;
	}
	
	
	/**
	 * Transform hour from H:MM to HH:MM
	 *
	 * @param $hour
	 */
	public static function transformHour($hour) {
		$res = null;
		if (strlen ( $hour ) > 0) {
			$tmp = explode ( ":", $hour );
			if (strlen ( $tmp [0] ) == 1) {
				$res = "0" . $hour;
			} else {
				$res = $hour;
			}
		}
		return $res;
	}
	
	/**
	 * Check the date.
	 *
	 * @param
	 *        	$date (DD/MM/YYYY)
	 *
	 * @return true if date is empty or is valid.
	 *
	 */
	public static function checkDate($date) {
		if (strlen ( $date ) > 0) {
			$res = date_parse_from_format ( "d/m/Y", $date );
			if ($res ['error_count'] > 0 || $res ['warning_count'] > 0) {
				return false;
			} else if (! checkdate ( explode ( "/", $date )[1], explode ( "/", $date )[0], explode ( "/", $date )[2] )) {
				return false;
			}
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Check the hour.
	 * 
	 * @param Hour (Format: (H)H:MM ) the hour to check.
	 *
	 * @return true if hour is empty or is valid.
	 *
	 */
	public static function checkHour($hour) {
		if (strlen ( $hour ) > 0) {
			$res = date_parse_from_format ( "H:i", $hour );
			if ($res ['error_count'] > 0 || $res ['warning_count'] > 0) {
				return false;
			}
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Return the day label from day number.
	* @param $dayNbr : 	the day number.
	*/
	public static function getDayLabel($dayNbr){
		$days = new DefinedEnum ( array (
				"0" => "Dimanche",
				"1" => "Lundi",
				"2" => "Mardi",
				"3" => "Mercredi",
				"4" => "Jeudi",
				"5" => "Vendredi",
				"6" => "Samedi"
		) );
		
		
		if(isset($dayNbr) && $dayNbr >= 0 && $dayNbr <= 6){
			$temp = ''.$dayNbr;
			return $days->$temp;
		}else{
			return '-';
		}
	}

}

?>