<?php
include_once $_SERVER ['DOCUMENT_ROOT'] . '/dao/GenericDAO.php';
include_once $_SERVER ['DOCUMENT_ROOT'] . '/bo/Account.php';
class AccountDAO extends GenericDAO {
	
	/**
	 * 
	 * @param $login
	 * @return NULL|Account
	 */
	function getAccountByPk($login) {
		$queryString = "SELECT * FROM accounts a where a.login = :login";
		
		$stmt = $this->connexion->prepare ( $queryString ); // on va chercher tous les membres de la table qu'on trie par ordre croissant
		$stmt->execute ( array (
				'login' => $login 
		) );
		$stmt->setFetchMode ( PDO::FETCH_OBJ ); // on dit qu'on veut que le résultat soit récupérable sous forme d'objet
		$ligne = $stmt->fetch ();
		
		if ($ligne) {
			$account = new Account ( $ligne->login, $ligne->password);
		}else{
			return null;
		}
		
		return $account;
	}	
}
?>