<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountRelationship extends Model
{
	protected $table = 'account_relationship';


	public function sender(): BelongsTo
	{
		return $this->belongsTo('App\User');
	}
}
