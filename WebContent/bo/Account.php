<?php


class Account
{
	public $login;
	public $password;

	/**
	 * 
	 * @param  $login
	 * @param  $password
	 */
	function __construct($login,$password) {
		$this->login=$login;
		$this->password=$password;		
	}

}
?>