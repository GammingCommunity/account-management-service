<?php

namespace App\GraphQL\Entities\Input;

use App\AccountPrivacyType;

class AccountBirthYearInput
{
	/**
	 * @var string
	 */
	public $year;

	/**
	 * @var int
	 */
	public $privacyTypeId;

	/**
	 * @param array $args
	 */
	public function __construct(array $args)
	{
		$birthYear = $args['account']['birth_year'];
		$this->privacyTypeId = $birthYear['account_privacy_type_id'] ?? AccountPrivacyType::PUBLIC;
		$this->year = $birthYear['year'];
	}
}
