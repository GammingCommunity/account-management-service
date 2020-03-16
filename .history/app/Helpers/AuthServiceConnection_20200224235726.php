<?php

namespace App\Helpers;

use GuzzleHttp\Client;
use App\GraphQL\Entities\Result\ErrorResult;
use App\Common\AuthServiceResponse;

class AuthServiceConnection
{
	public static function request(string $method, string $path, array $option): AuthServiceResponse
	{
		$res = (new Client())->request($method, env('AUTH_SERVICE_URL') . $path, $option);
	}
}
