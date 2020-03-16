<?php

namespace App\GraphQL\Entities\Input;

use App\Enums\DbEnums\AccountPrivacyType;

class AccountBirthMonthInput
{
	/**
	 * @var string
	 */
	public $month;

	/**
	 * @var int
	 */
	public $privacyType;

	/**
	 * @param array $args
	 */
	public function __construct(array $args)
	{
		$birthMonth = $args['account']['birth_month'];
		$this->privacyType = $birthMonth['privacy_type'] ?? AccountPrivacyType::PUBLIC;
		$this->month = $birthMonth['month'];
	}
}
