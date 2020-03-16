<?php

namespace App\Common;

class AuthServiceJwtPayload
{
	/**
	 * @var int account_id
	 */
	public $id;

	/**
	 * @var int account_role
	 */
	public $rl;

	/**
	 * @var string login_session_id
	 */
	public $ss;

	/**
	 * @param string $jsonString
	 */
	public function __construct(string $jsonString)
	{
		$obj = json_decode($jsonString);
		$this->status = $obj->status;
		$this->data = $obj->data;
		$this->describe = $obj->describe;
	}
}
