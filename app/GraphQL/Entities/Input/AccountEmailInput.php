<?php

namespace App\GraphQL\Entities\Input;

use App\Enums\DbEnums\AccountPrivacyType;

class AccountEmailInput
{
	/**
	 * @var string
	 */
	public $email;

	/**
	 * @var int
	 */
	public $privacyType;

	/**
	 * @param array $args
	 */
	public function __construct(array $args)
	{
		$email = $args['account']['email'];
		$this->privacyType = $email['privacy_type'] ?? AccountPrivacyType::PUBLIC;
		$this->email = $email['email'];
	}
}
