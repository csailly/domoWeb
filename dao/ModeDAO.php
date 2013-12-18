<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/dao/GenericDAO.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/bo/Mode.php';

class ModeDAO extends GenericDAO {

	/**
	 * 	Recherche un mode.
	 *	@param modeId : identifiant du mode.
	 *	@return Mode
	 */
	function getModeById($modeId) {
		$stmt=$this->connexion->prepare("SELECT * FROM mode m where m.id = :id"); // on va chercher tous les membres de la table qu'on trie par ordre croissant
		$stmt->execute(array( 'id' => $modeId ));
		$stmt->setFetchMode(PDO::FETCH_OBJ); // on dit qu'on veut que le résultat soit récupérable sous forme d'objet
		$ligne = $stmt->fetch();
			
		if ($ligne){
			$mode = new Mode($ligne->id,$ligne->libelle,$ligne->cons,$ligne->max);
		}
		return $mode;
	}

}
?>