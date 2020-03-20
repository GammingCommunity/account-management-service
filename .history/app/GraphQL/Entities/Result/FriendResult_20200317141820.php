<?php

namespace App\GraphQL\Entities\Result;

use App\Account;

class FriendResult
{
	/**
	 * @var Account
	 */
	public $account;

	/**
	 * @var string
	 */
	public $updated_at;

	/**
	 * @param Account $account
	 * @param string $updated_at
	 */
	public function __construct(Account $account = null, string $updated_at = '')
	{
		$this->relationship = $relationship;
		$this->account = $account;
		$this->updated_at = $updated_at;
	}
}
