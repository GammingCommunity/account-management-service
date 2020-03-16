<?php

namespace App\Common\AuthService;

use GuzzleHttp\Client;
use App\GraphQL\Entities\Result\ErrorResult;
use App\Common\AuthService\AuthServiceResponse;

class AuthServiceConnection
{
	public static function request(string $method, string $path, array $option): AuthServiceResponse
	{
		$res = (new Client())->request($method, env('AUTH_SERVICE_URL') . $path, $option);

		if ($res->getStatusCode() === 200) {
			return new AuthServiceResponse($res->getBody()->getContents());
		} else {
			ErrorResult::exit('Status code: ' . $res->getStatusCode());
		}
	}
}
