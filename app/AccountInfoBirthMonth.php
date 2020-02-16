<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountInfoBirthMonth extends Model
{
	protected $table = 'account_info_birth_month';
	protected $fillable = ['month', 'account_privacy_type_id'];
	public $timestamps = false;
}
