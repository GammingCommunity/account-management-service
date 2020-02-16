<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountInfoPhone extends Model
{
	protected $table = 'account_info_phone';
	protected $fillable = ['phone', 'account_privacy_type_id'];
	public $timestamps = false;
}
