<?php

namespace App\GraphQL\Entities\Input;

use App\AccountPrivacyType;

class AccountEmailInput
{
	/**
	 * @var string
	 */
	public $email;

	/**
	 * @var int
	 */
	public $privacyTypeId;

	/**
	 * @param array $args
	 */
	public function __construct(array $args)
	{
		$email = $args['account']['email'];
		$this->privacyTypeId = $email['account_privacy_type_id'] ?? AccountPrivacyType::PUBLIC;
		$this->email = $email['email'];
	}
}
