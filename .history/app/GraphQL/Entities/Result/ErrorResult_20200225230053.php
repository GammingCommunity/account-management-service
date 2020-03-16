<?php

namespace App\GraphQL\Entities\Result;

class ErrorResult
{
	/**
	 * @var ErrorInfo
	 */
	public $error;

	/**
	 * @param string $describe
	 */
	public function __construct(string $describe)
	{
		$this->error = new ErrorInfo($describe);
	}

	public static function exit(string $text)
	{
		http_response_code(500);
		header('Content-Type: application/json');
		if (!config('app.debug')) {
			$text = hash('sha256', $text);
		}
		exit(json_encode(new ErrorResult($text)));
	}
}

class ErrorInfo
{
	/**
	 * @var string
	 */
	public $describe;

	/**
	 * @param string $describe
	 */
	public function __construct(string $describe)
	{
		$this->describe = $describe;
	}
}
