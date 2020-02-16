<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Account extends Model
{
	protected $table = 'account';
	protected $hidden = ['password'];
	protected $fillable = ['name', 'login_name', 'password', 'describe', 'account_setting_id'];

	public function birthMonth(): BelongsTo
	{
		return $this->belongsTo(AccountInfoBirthMonth::class, 'account_info_birth_month_id', 'id');
	}

	public function birthYear(): BelongsTo
	{
		return $this->belongsTo(AccountInfoBirthYear::class, 'account_info_birth_year_id', 'id');
	}

	public function email(): BelongsTo
	{
		return $this->belongsTo(AccountInfoEmail::class, 'account_info_email_id', 'id');
	}

	public function setting(): BelongsTo
	{
		return $this->belongsTo(AccountSetting::class, 'account_setting_id', 'id');
	}

	public function phone(): BelongsTo
	{
		return $this->belongsTo(AccountInfoPhone::class, 'account_info_phone_id', 'id');
	}
}
