<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Account extends Model
{
	protected $table = 'account';
	protected $hidden = ['password'];
	protected $fillable = ['name', 'describe'];


	public function setting(): HasOne
	{
		return $this->hasOne(AccountSetting::class, 'id', 'id');
	}
}
