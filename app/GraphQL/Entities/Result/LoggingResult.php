<?php

namespace App\GraphQL\Entities\Result;

use App\Account;
use App\Enums\LoggingResultStatus;

class LoggingResult
{
	/**
	 * @var int
	 */
	public $status;

	/**
	 * @var string
	 */
	public $token;

	/**
	 * @var Account
	 */
	public $account;

	/**
	 * @var string
	 */
	public $describe;

	/**
	 * @param int $status
	 * @param string $token
	 * @param Account $account
	 * @param string $describe
	 */
	public function __construct(int $status = LoggingResultStatus::FAIL, string $token = null, Account $account = null, string $describe = null)
	{
		$this->status = $status;
		$this->token = $token;
		$this->account = $account;
		$this->describe = $describe;
	}
}
