<?php

namespace App;

use App\Enums\DbEnums\AccountRole;
use App\Enums\DbEnums\AccountStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Account extends Model
{
	protected $table = 'account';
	protected $hidden = ['password'];
	protected $fillable = ['name', 'describe', 'role', 'status'];

	static public function createModel(string $name, string $describe = ''): Account{
		return parent::create([
			'name' => $name,
			'describe' => $describe,
			'role' => AccountRole::USER,
			'status' => AccountStatus::ACTIVATED,
		]);
	}

	static public function deleteModel(string $name, string $describe = ''): Account
	{
		return parent::create([
			'name' => $name,
			'describe' => $describe,
			'role' => AccountRole::USER,
			'status' => AccountStatus::ACTIVATED,
		]);
	}

	public function setting(): HasOne
	{
		return $this->hasOne(AccountSetting::class, 'id', 'id');
	}
}
