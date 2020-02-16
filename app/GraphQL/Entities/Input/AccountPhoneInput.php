<?php

namespace App\GraphQL\Entities\Input;

use App\AccountPrivacyType;

class AccountPhoneInput
{
	/**
	 * @var string
	 */
	public $phone;

	/**
	 * @var int
	 */
	public $privacyTypeId;

	/**
	 * @param array $args
	 */
	public function __construct(array $args)
	{
		$phone = $args['account']['phone'];
		$this->privacyTypeId = $phone['account_privacy_type_id'] ?? AccountPrivacyType::PUBLIC;
		$this->phone = $phone['phone'];
	}
}
