<?php
class GenericDAO {
	//Connexion à la base de données
	protected $connexion;

	/**
	 *
	 * @param $connexion
	 */
	function __construct($connexion){
		$this->connexion=$connexion;
	}
}
?>