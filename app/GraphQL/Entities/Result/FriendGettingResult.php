<?php

namespace App\GraphQL\Entities\Result;

use App\Account;

class FriendGettingResult
{
	/**
	 * @var Account
	 */
	public $friend;

	/**
	 * @var string
	 */
	public $updated_at;

	/**
	 * @param Account $friend
	 * @param string $updated_at
	 */
	public function __construct(Account $friend = null, string $updated_at = null)
	{
		$this->friend = $friend;
		$this->updated_at = $updated_at;
	}
}
