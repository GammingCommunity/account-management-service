<?php

namespace App\GraphQL\Entities\Result;

use App\Enums\AccountEditingResultStatus;

class AccountEditingResult
{
	/**
	 * @var int
	 */
	public $status;

	/**
	 * @var string
	 */
	public $describe;

	/**
	 * @param int $status
	 * @param string $describe
	 */
	public function __construct(int $status = AccountEditingResultStatus::FAIL, string $describe = null)
	{
		$this->status = $status;
		$this->describe = $describe;
	}
}
