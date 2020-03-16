<?php

namespace App\GraphQL\Entities\Result;

use App\Account;
use App\AccountRelationshipType;
use App\Enums\ResultEnums\AccountLookingResultStatus;

class AccountLookingResult
{
	/**
	 * @var int
	 */
	public $status;

	/**
	 * @var int
	 */
	public $relationship;

	/**
	 * @var Account
	 */
	public $account;

	/**
	 * @param int $status
	 * @param int $relationship
	 * @param Account $account
	 */
	public function __construct(int $status = AccountLookingResultStatus::FAIL, int $relationship = null, Account $account = null)
	{
		$this->status = $status;
		$this->relationship = $relationship;
		$this->account = $account;
	}
}
