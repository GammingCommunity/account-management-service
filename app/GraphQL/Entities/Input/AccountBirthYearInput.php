<?php

namespace App\GraphQL\Entities\Input;

use App\Enums\DbEnums\AccountPrivacyType;

class AccountBirthYearInput
{
	/**
	 * @var string
	 */
	public $year;

	/**
	 * @var int
	 */
	public $privacyType;

	/**
	 * @param array $args
	 */
	public function __construct(array $args)
	{
		$birthYear = $args['account']['birth_year'];
		$this->privacyType = $birthYear['privacy_type'] ?? AccountPrivacyType::PUBLIC;
		$this->year = $birthYear['year'];
	}
}
