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
		$response = array (
				'result' => 'success'
		);
		
		try{		
			$stmt=$this->connexion->prepare("SELECT * FROM mode m where m.id = :id"); // on va chercher tous les membres de la table qu'on trie par ordre croissant
			$stmt->execute(array( 'id' => $modeId ));
			$stmt->setFetchMode(PDO::FETCH_OBJ); // on dit qu'on veut que le résultat soit récupérable sous forme d'objet
			$ligne = $stmt->fetch();
			
			if ($ligne){
				$mode = new Mode($ligne->id,$ligne->libelle,$ligne->cons,$ligne->max);
			}
		
		} catch ( PDOException $e ) {
			$response ['result'] = 'error';
			$errorsMsgs = array ();
			$errorsMsgs ["exception"] = $e->getMessage ();
			$errors = array (
					'errorsMsgs' => $errorsMsgs
			);
			$response ['errors'] = $errors;
		}
		//TODO Renvoyer json
		
		return $mode;
	}
	
	/**
	 * Renvoie la liste des Modes
	 * @return multitype:Mode
	 */
	function getAllModes() {
		//TODO Renvoyer json et mettre exception
		$queryString = "SELECT * FROM mode";
		$list = array ();
		
		$resultats = $this->connexion->query ($queryString);
		$resultats->setFetchMode ( PDO::FETCH_OBJ ); // on dit qu'on veut que le résultat soit récupérable sous forme d'objet
		while ( $ligne = $resultats->fetch () ) 		// on récupère la liste des membres
		{
			$mode = new Mode($ligne->id,$ligne->libelle,$ligne->cons,$ligne->max);
			$list [] = $mode;
		}
		$resultats->closeCursor (); // on ferme le curseur des résultats
		
		return $list;		
		
	}
	
	/**
	 * Crée un nouveau mode
	 * @param  $label : le libellé du mode
	 * @param  $cons : la température de consigne
	 * @param  $max	: la température maximale
	 */
	function createMode($label, $cons, $max){
		//TODO check parameters
		$response = array (
				'result' => 'success'
		);
		
		// Execute the query
		$queryString = "INSERT INTO mode(libelle, cons, max) VALUES ( :label, :cons, :max)";
		try {
			$stmt = $this->connexion->prepare ( $queryString );
			$stmt->execute ( array (
					'label' => $label,
					'cons' => $cons,
					'max' => $max
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
	 * Met à jour le mode
	 * @param  $modeId	:	l'identifiant du mode à mettre à jour
	 * @param  $label	:	le libellé du mode
	 * @param  $cons	:	la température de consigne
	 * @param  $max		:	la température maximale
	 */
	function updateMode($modeId, $label, $cons, $max){
		//TODO check parameters
		$response = array (
				'result' => 'success'
		);
		
		// Execute the query
		$queryString = "UPDATE mode SET libelle = :label, cons = :cons, max = :max WHERE id = :modeId";
		try {
			$stmt = $this->connexion->prepare ( $queryString );
			$stmt->execute ( array (
					'label' => $label,
					'cons' => $cons,
					'max' => $max,
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
	 * Supprime un mode.
	 *
	 * @param
	 *        	modeId : l'identifiant du mode à supprimer.
	 */
	function delete($modeId) {
		$response = array (
				'result' => 'success'
		);
	
		$queryString = "DELETE FROM mode WHERE id = :modeId";
		try {
			$stmt = $this->connexion->prepare ( $queryString );
			$stmt->execute ( array (
					'$modeId' => $modeId
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

}
?>