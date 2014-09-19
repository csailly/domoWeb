<?php
include_once $_SERVER ['DOCUMENT_ROOT'] . '/dao/GenericDAO.php';
include_once $_SERVER ['DOCUMENT_ROOT'] . '/bo/Parameter.php';
class ParameterDAO extends GenericDAO {
	
	/**
	 * 
	 * @param $code
	 * @return NULL|Parameter
	 */
	function getParameter($code) {
		$queryString = "SELECT * FROM parametrage p where p.code = :code";
		
		$stmt = $this->connexion->prepare ( $queryString ); // on va chercher tous les membres de la table qu'on trie par ordre croissant
		$stmt->execute ( array (
				'code' => $code 
		) );
		$stmt->setFetchMode ( PDO::FETCH_OBJ ); // on dit qu'on veut que le résultat soit récupérable sous forme d'objet
		$ligne = $stmt->fetch ();
		
		$parameter = null;
		
		if ($ligne) {
			$parameter = new Parameter ( $ligne->code,$ligne->type,$ligne->valeur,$ligne->commentaire,$ligne->valeurs);
		}
		
		return $parameter;
	}
	
	/**
	 * Renvoie les paramètres
	 * @return multitype:Parameter
	 */
	function getAllParameters() {
		$queryString = "SELECT * FROM parametrage";
		$list = array ();
	
		$resultats = $this->connexion->query ( $queryString );
		$resultats->setFetchMode ( PDO::FETCH_OBJ ); // on dit qu'on veut que le résultat soit récupérable sous forme d'objet
		while ( $ligne = $resultats->fetch () ) 		// on récupère la liste des membres
		{
			$parameter = new Parameter ( $ligne->code,$ligne->type,$ligne->valeur,$ligne->commentaire,$ligne->valeurs);
			$list [] = $parameter;
		}
		$resultats->closeCursor (); // on ferme le curseur des résultats
	
		return $list;
	}
	
	
	/**
	 *
	 * @param $code
	 * @return NULL|Parameter
	 */
	function saveParameter($code, $value) {
		$queryString = "UPDATE parametrage  set valeur = :value where code = :code";
	
		$stmt = $this->connexion->prepare ( $queryString );
		$stmt->execute ( array (
				'value' => $value, 'code' => $code
		) );
	}
}
?>