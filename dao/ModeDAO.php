<?php
include_once $_SERVER ['DOCUMENT_ROOT'] . '/dao/GenericDAO.php';
include_once $_SERVER ['DOCUMENT_ROOT'] . '/bo/Mode.php';
class ModeDAO extends GenericDAO {
	
	/**
	 * Recherche un mode.
	 *
	 * @param
	 *        	modeId : identifiant du mode.
	 * @return Mode
	 */
	function getModeById($modeId) {
		$queryString = "SELECT * FROM mode m where m.id = :id";
		
		$stmt = $this->connexion->prepare ( $queryString ); // on va chercher tous les membres de la table qu'on trie par ordre croissant
		$stmt->execute ( array (
				'id' => $modeId 
		) );
		$stmt->setFetchMode ( PDO::FETCH_OBJ ); // on dit qu'on veut que le résultat soit récupérable sous forme d'objet
		$ligne = $stmt->fetch ();
		
		if ($ligne) {
			$mode = new Mode ( $ligne->id, $ligne->libelle, $ligne->cons, $ligne->max );
		}
		
		return $mode;
	}
	
	/**
	 * Renvoie la liste des Modes
	 *
	 * @return multitype:Mode
	 */
	function getAllModes() {
		$queryString = "SELECT * FROM mode ORDER BY libelle ASC";
		$list = array ();
		
		$resultats = $this->connexion->query ( $queryString );
		$resultats->setFetchMode ( PDO::FETCH_OBJ ); // on dit qu'on veut que le résultat soit récupérable sous forme d'objet
		while ( $ligne = $resultats->fetch () ) 		// on récupère la liste des membres
		{
			$mode = new Mode ( $ligne->id, $ligne->libelle, $ligne->cons, $ligne->max );
			$list [] = $mode;
		}
		$resultats->closeCursor (); // on ferme le curseur des résultats
		
		return $list;
	}
	
	/**
	 * Crée un nouveau mode
	 *
	 * @param $label :
	 *        	le libellé du mode
	 * @param $cons :
	 *        	la température de consigne
	 * @param $max :
	 *        	température maximale
	 */
	function createMode($label, $cons, $max) {
		$queryString = "INSERT INTO mode(libelle, cons, max) VALUES ( :label, :cons, :max)";
		
		$stmt = $this->connexion->prepare ( $queryString );
		$stmt->execute ( array (
				'label' => $label,
				'cons' => $cons,
				'max' => $max 
		) );
	}
	
	/**
	 * Met à jour le mode
	 *
	 * @param $modeId :
	 *        	mode à mettre à jour
	 * @param $label :
	 *        	du mode
	 * @param $cons :
	 *        	de consigne
	 * @param $max :
	 *        	maximale
	 */
	function updateMode($modeId, $label, $cons, $max) {
		$queryString = "UPDATE mode SET libelle = :label, cons = :cons, max = :max WHERE id = :modeId";
		
		$stmt = $this->connexion->prepare ( $queryString );
		$stmt->execute ( array (
				'label' => $label,
				'cons' => $cons,
				'max' => $max,
				'modeId' => $modeId 
		) );
	}
	
	/**
	 * Supprime un mode.
	 *
	 * @param
	 *        	modeId : l'identifiant du mode à supprimer.
	 */
	function delete($modeId) {
		$queryString = "DELETE FROM mode WHERE id = :modeId";
		
		$stmt = $this->connexion->prepare ( $queryString );
		$stmt->execute ( array (
				'$modeId' => $modeId 
		) );
	}
}
?>