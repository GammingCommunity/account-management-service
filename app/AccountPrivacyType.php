<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountPrivacyType extends Model
{
	protected $table = 'account_privacy_type';
	public $timestamps = false;
	public $incrementing = false;

	const PRIVATE = -1;
	const PUBLIC = 1;
	const FRIEND = 2;
}
