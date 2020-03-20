<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Account extends Model
{
	protected $table = 'account';
	protected $hidden = ['password'];
	protected $fillable = ['name', 'describe', 'account_setting_id'];


	public function setting(): BelongsTo
	{
		return $this->belongsTo(AccountSetting::class, 'account_setting_id', 'id');
	}
}
