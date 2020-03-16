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
	 * @param int $relationship
	 * @param Account $account
	 */
	public function __construct(int $relationship = null, Account $account = null)
	{
		$this->relationship = $relationship;
		$this->account = $account;
	}
}
