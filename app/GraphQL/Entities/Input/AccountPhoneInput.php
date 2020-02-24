<?php

namespace App\GraphQL\Entities\Input;

use App\Enums\DbEnums\AccountPrivacyType;

class AccountPhoneInput
{
	/**
	 * @var string
	 */
	public $phone;

	/**
	 * @var int
	 */
	public $privacyType;

	/**
	 * @param array $args
	 */
	public function __construct(array $args)
	{
		$phone = $args['account']['phone'];
		$this->privacyType = $phone['privacy_type'] ?? AccountPrivacyType::PUBLIC;
		$this->phone = $phone['phone'];
	}
}
