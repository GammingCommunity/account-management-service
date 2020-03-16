<?php

namespace App\Helpers;

use GuzzleHttp\Client;

class AuthServiceConnection
{
	public static function request(string $method, string $path, array $option)
	{
		$res = (new Client())->request($method, env('AUTH_SERVICE_URL') . $path, $option);
	}
}
