<?php

namespace App\GraphQL\Entities\Input;

use App\AccountPrivacyType;

class AccountBirthMonthInput
{
	/**
	 * @var string
	 */
	public $month;

	/**
	 * @var int
	 */
	public $privacyTypeId;

	/**
	 * @param array $args
	 */
	public function __construct(array $args)
	{
		$birthMonth = $args['account']['birth_month'];
		$this->privacyTypeId = $birthMonth['account_privacy_type_id'] ?? AccountPrivacyType::PUBLIC;
		$this->month = $birthMonth['month'];
	}
}
