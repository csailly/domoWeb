<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/bo/Mode.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/utils/Enum.php';


class Periode
{
	public $id;
	public $jour;
	public $dateDebut;
	public $dateFin;
	public $heureDebut;
	public $heureFin;
	public $modeId;

	private $jours;


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
		$this->jours = new DefinedEnum(array("1" => "Lundi", "2" => "Mardi", "3" => "Mercredi", "4" => "Jeudi", "5" => "Vendredi", "6" => "Samedi", "7" => "Dimanche"));
	}

	/**
	 *
	 * @return string
	 */
	function getLibelleJour(){
		if(isset($this->jour) && $this->jour > 0 && $this->jour < 8){
			$temp = ''.$this->jour;
			return $this->jours->$temp;
		}else{
			return '-';
		}
	}
}
?>