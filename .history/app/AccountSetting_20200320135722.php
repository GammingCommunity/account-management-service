<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountSetting extends Model
{
	protected $table = 'account_setting';
	public $timestamps = false;
	protected $fillable = ['id'];

	static public function createModel(int $id): AccountSetting
	{
		return parent::create([
			'id' => $id
		]);
	}
}
