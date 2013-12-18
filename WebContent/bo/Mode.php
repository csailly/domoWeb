<?php
class Mode{
	public $id;
	public $libelle;
	public $cons;
	public $max;

	/**
	 *
	 * @param  $id
	 * @param  $libelle
	 * @param  $cons
	 * @param  $max
	 */
	function __construct($id,$libelle,$cons,$max){
		$this->id=$id;
		$this->libelle=$libelle;
		$this->cons=$cons;
		$this->max=$max	;
	}		
}
?>