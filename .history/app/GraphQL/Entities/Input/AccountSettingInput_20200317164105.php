<?php

namespace App\GraphQL\Entities\Input;

class AccountSettingInput
{
	/**
	 * @var bool
	 */
	public $anonymous;
 
	/**
	 * @var int
	 */
	public $birthyear_privacy;
 
	/**
	 * @var int
	 */
	public $birthmonth_privacy;
 
	/**
	 * @var int
	 */
	public $email_privacy;
 
	/**
	 * @var int
	 */
	public $phone_privacy;
 
	/**
	 * @param array $args
	 */
	public function __construct(array $args)
	{
		$setting = $args['account']['setting'];
		$this->anonymous = $setting['anonymous'];
	}
}
