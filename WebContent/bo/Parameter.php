<?php

class Parameter
{
	public $code;
	public $type;
	public $value;
	public $comment;
	public $values;

	/**
	 * 
	 * @param  $code
	 * @param  $type
	 * @param  $value
	 * @param  $comment
	 * @param  $values
	 */
	function __construct($code,$type,$value,$comment,$values) {
		$this->code=$code;
		$this->type=$type;
		$this->value=$value;
		$this->comment=$comment;	
		$this->values=$values;
	}

}
?>