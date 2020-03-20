<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountRelationship extends Model
{
	protected $table = 'account_relationship';


	public function sender(): BelongsTo
	{
		return $this->belongsTo(Account::class, 'sender_account_id', 'id');
	}

	public function receiver(): BelongsTo
	{
		return $this->belongsTo(Account::class, 'receiver_account_id', 'id');
	}
}
