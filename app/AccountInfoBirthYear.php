<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountInfoBirthYear extends Model
{
	protected $table = 'account_info_birth_year';
	protected $fillable = ['year', 'account_privacy_type_id'];
	public $timestamps = false;
}
