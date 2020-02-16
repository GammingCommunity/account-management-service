<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountSetting extends Model
{
	protected $table = 'account_setting';
	public $timestamps = false;
	protected $fillable = ['anonymous'];

	public static function createDefaultSetting(): ?AccountSetting{
		return AccountSetting::create([
			'anonymous' => false
		]);
	}
}
