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
		if (strpos($token, '.') === false) {
			ErrorResult::exit('Token format error.');
		}
		$payloadBase64Url = explode('.', $token)[1];
		$payloadBase64 = str_replace('-', '+', str_replace('_', '/', $payloadBase64Url));
		$obj = json_decode(base64_decode($payloadBase64)); //decode jwt payload
		if ($obj->id && $obj->rl && $obj->ss) {
			$this->accountId = $obj->id;
			$this->accountRole = $obj->rl;
			$this->session = $obj->ss;
		} else {
			ErrorResult::exit('JWT payload format error.');
		}
	}
}
