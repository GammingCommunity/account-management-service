<?php

namespace App\GraphQL\Entities\Result;

use App\Account;

class FriendRequestingResult
{
	/**
	 * @var Account
	 */
	public $sender;

	/**
	 * @var string
	 */
	public $updated_at;

	/**
	 * @param Account $sender
	 * @param string $updated_at
	 */
	public function __construct(Account $sender = null, string $updated_at = null)
	{
		$this->sender = $sender;
		$this->updated_at = $updated_at;
	}
}
