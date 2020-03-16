<?php

namespace App\GraphQL\Entities\Result;

class ErrorResult
{
	/**
	 * @var ErrorInfo
	 */
	public $error;

	/**
	 * @param mixed $describe
	 */
	public function __construct($describe)
	{
		$this->error = new ErrorInfo($describe);
	}

	public static function exit($content)
	{
		http_response_code(500);
		header('Content-Type: application/json');
		if (!config('app.debug')) {
			$content = hash('sha256', $content);
		}
		exit(json_encode(new ErrorResult($content)));
	}
}

class ErrorInfo
{
	/**
	 * @var mixed
	 */
	public $describe;

	/**
	 * @param mixed $describe
	 */
	public function __construct($describe)
	{
		$this->describe = $describe;
	}
}
