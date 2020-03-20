<?php

namespace App\GraphQL\Entities\Input;

class AccountSettingInput
{
	/**
	 * @var bool
	 */
	public $anonymous;

	/**
	 * @param array $args
	 */
	public function __construct(array $args)
	{
		$setting = $args['account']['setting'];
		$this->anonymous = $setting['anonymous'];
	}
}
