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
		
		$this->anonymous = $setting['anonymous'] ?? null;
		$this->birthyear_privacy = $setting['birthyear_privacy'] ?? null;
		$this->birthmonth_privacy = $setting['birthmonth_privacy'] ?? null;
		$this->email_privacy = $setting['email_privacy'] ?? null;
		$this->phone_privacy = $setting['phone_privacy'] ?? null;
		$this->allow_email_to_search = $setting['allow_email_to_search'] ?? null;
		$this->allow_phone_to_search = $setting['allow_phone_to_search'] ?? null;
		$this->not_receive_messages_from_strangers = $setting['not_receive_messages_from_strangers'] ?? null;
	}
}
