<?php
include_once $_SERVER ['DOCUMENT_ROOT'] . '/dao/GenericDAO.php';
class TemperatureDAO extends GenericDAO {
	/**
	 *
	 * @param DateTime $startDate
	 * @return multitype:number multitype:number
	 */
	function getAllTemperatures($startDate, $sonde) {
		date_default_timezone_set ( 'Europe/Paris' );

		$queryString = "SELECT * FROM histo_temp h  where date >= :startDate and sonde = :sonde order by date, heure";

		$stmt = $this->connexion->prepare ( $queryString ); // on va chercher tous les membres de la table qu'on trie par ordre croissant
		$stmt->execute ( array (
				'startDate' => $startDate->format('Y-m-d'),
				'sonde' => $sonde
		) );
		$stmt->setFetchMode ( PDO::FETCH_OBJ ); // on dit qu'on veut que le résultat soit récupérable sous forme d'objet
		$ligne = $stmt->fetch ();

		if($ligne){
			$startDateTime = $ligne->date.'T'.$ligne->heure.' UTC';
			$startDateTime_ms = 1000 * strtotime($startDateTime);
		}

		while ( $ligne ) 		// on récupère la liste des membres
		{
			$datas [] = array (
					1000 * strtotime ( $ligne->date . 'T' . $ligne->heure . ' UTC' ),
					1 * $ligne->temp
			);
			$ligne = $stmt->fetch ();
		}
		$stmt->closeCursor (); // on ferme le curseur des résultats

		$result = array('startTime' => $startDateTime_ms, 'datas' => $datas);

		return $result;
	}
}
?>
