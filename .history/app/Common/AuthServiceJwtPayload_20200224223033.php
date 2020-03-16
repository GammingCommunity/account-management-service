<?php

namespace App\Common;

use App\GraphQL\Entities\Result\ErrorResult;

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
		if(strpos($token, '.') === false){
			ErrorResult::exit('Token format error.');
		}
		$obj = json_decode(explode('.', $token)[1]); //decode jwt payload
		$this->accountId = $obj->id;
		$this->accountRole = $obj->rl;
		$this->session = $obj->ss;
	}
}
