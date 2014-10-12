<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/bo/Mode.php';

class Periode
{
	public $id;
	public $jour;
	public $dateDebut;
	public $dateFin;
	public $heureDebut;
	public $heureFin;
	public $modeId;

	/**
	 *
	 * @param  $id
	 * @param  $jour
	 * @param  $dateDebut
	 * @param  $dateFin
	 * @param  $heureDebut
	 * @param  $heureFin
	 * @param  $modeId
	 */
	function __construct($id,$jour,$dateDebut,$dateFin,$heureDebut,$heureFin,$modeId) {
		$this->id=$id;
		$this->jour=$jour;
		$this->dateDebut=$dateDebut;
		$this->dateFin=$dateFin;
		$this->heureDebut=$heureDebut;
		$this->heureFin=$heureFin;
		$this->modeId=$modeId;		
	}

}
?>