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
			$parameter = new Parameter ( $ligne->code,$ligne->type,$ligne->valeur,$ligne->commentaire);
		}
		
		return $parameter;
	}
	
	
	/**
	 *
	 * @param $code
	 * @return NULL|Parameter
	 */
	function saveParameter($code, $value) {
		$queryString = "UPDATE parametrage  set valeur = :value where code = :code";
	
		$stmt = $this->connexion->prepare ( $queryString ); // on va chercher tous les membres de la table qu'on trie par ordre croissant
		$stmt->execute ( array (
				'value' => $value, 'code' => $code
		) );
	}
}
?>