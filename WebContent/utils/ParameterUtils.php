<?php

class ParameterUtils {
	
	/**
	 * Renvoie true si le paramètre est un Boolean TRUE
	 * @param  $parameter
	 * @return boolean
	 */
	public static function isTrueValue($parameter){
		if ("BOOLEAN" === $parameter->type && isset($parameter->values)){
			list($trueMask, $falseMask) =  explode(";",$parameter->values,2);
			return $parameter->value === $trueMask; 
		}
		return false;
	}

	public static function getBooleanTrueValue($parameter){
		if ("BOOLEAN" === $parameter->type && isset($parameter->values)){
			list($trueMask, $falseMask) =  explode(";",$parameter->values,2);
			return $trueMask;
		}
		//Default
		return "TRUE";
	}
	
	public static function getBooleanFalseValue($parameter){
		if ("BOOLEAN" === $parameter->type && isset($parameter->values)){
			list($trueMask, $falseMask) =  explode(";",$parameter->values,2);
			return $falseMask;
		}
		//Default
		return "FALSE";
	}
	
	public static function getSelectValues($parameter){
		if ("STRING" === $parameter->type && isset($parameter->values)){
			return explode(";",$parameter->values);
		}
		return null;
	}
	
	public static function isSelectParameter($parameter){
		return "STRING" === $parameter->type && isset($parameter->values);
	}
	
	public static function isBooleanParameter($parameter){
		return "BOOLEAN" === $parameter->type && isset($parameter->values);
	}
}

?>