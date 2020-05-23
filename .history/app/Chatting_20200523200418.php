<?php

namespace App;

use App\Enums\DbEnums\AccountRole;
use App\Enums\DbEnums\AccountStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Account extends Model
{
	protected $table = 'chatting';
	protected $fillable = ['content', 'info'];

	static public function createModel(string $name, string $describe = ''): Account
	{
		$acc = parent::create([
			'name' => $name,
			'describe' => $describe,
			'role' => AccountRole::USER,
			'status' => AccountStatus::ACTIVATED,
		]);
		AccountSetting::createModel($acc->id);

		return $acc;
	}

	public function deleteModel(): bool
	{
		return (!$this->setting || ($this->setting && $this->setting->delete())) && $this->delete();
	}

	public function setting(): HasOne
	{
		return $this->hasOne(AccountSetting::class, 'id', 'id');
	}
}
