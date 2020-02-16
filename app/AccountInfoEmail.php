<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountInfoEmail extends Model
{
	protected $table = 'account_info_email';
	protected $fillable = ['email', 'account_privacy_type_id'];
	public $timestamps = false;
}
