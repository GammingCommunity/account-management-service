<?php

namespace App\Common;

class AuthServiceJwtPayload
{
	/**
	 * @var int
	 */
	public $accountId;

	/**
	 * @var int 
	 */
	public $accountRole;

	/**
	 * @var string
	 */
	public $session;

	/**
	 * @param string $token
	 */
	public function __construct(string $token)
	{
		$obj = json_decode(explode('.', $token)[1]);
		$this->accountId = $obj->id;
		$this->accountRole = $obj->rl;
		$this->session = $obj->ss;
	}
}
