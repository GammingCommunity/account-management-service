<?php

namespace App\GraphQL\Entities\Result;

use App\Account;

class FriendGettingResult
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
	public function __construct(Account $account = null, string $updated_at = null)
	{
		$this->account = $account;
		$this->updated_at = $updated_at;
	}
}
