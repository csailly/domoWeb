<?php

class Parameter
{
	public $code;
	public $type;
	public $value;
	public $comment;

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
	function __construct($code,$type,$value,$comment) {
		$this->code=$code;
		$this->type=$type;
		$this->value=$value;
		$this->comment=$comment;	
	}

}
?>