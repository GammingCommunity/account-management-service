<?php

namespace App\GraphQL\Entities\Result;

use App\Account;

class AccountLookingResult
{
	/**
	 * @var int
	 */
	public $relationship;

	/**
	 * @var Account
	 */
	public $account;

	/**
	 * @var string
	 */
	public $describe;

	/**
	 * @param int $relationship
	 * @param Account $account
	 * @param string $describe
	 */
	public function __construct(int $relationship = null, Account $account = null, string $describe = '')
	{
		$this->relationship = $relationship;
		$this->account = $account;
		$this->describe = $describe;
	}
}
