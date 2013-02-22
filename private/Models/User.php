<?php
namespace Models;

class User extends \rp\Directory\Person{

	/**
	 * If the user is acting as themselves or delegating as another user
	 * @var boolean
	 */
	public $acting_as_self = false;


}
?>