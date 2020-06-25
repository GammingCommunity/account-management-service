<?php

namespace App\GraphQL\Entities\Result;

class ResultCRUD
{
	/**
	 * @var int
	 */
	public $status;

	/**
	 * @var string
	 */
	public $payload;

	/**
	 * @var boolean
	 */
	public $success;

	/**
	 * @var string
	 */
	public $message;

	/**
	 * @param int $status
	 * @param string $payload
	 * @param boolean $success
	 * @param string $message
	 */
	public function __construct(int $status = 200, string $payload = null, bool $success = false, string $message = null)
	{
		$this->status = $status;
		$this->payload = $payload;
		$this->success = $success;
		$this->message = $message;
	}
}
