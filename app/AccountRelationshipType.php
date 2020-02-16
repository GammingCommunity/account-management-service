<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountRelationshipType extends Model
{
	protected $table = 'account_relationship_type';
	public $timestamps = false;
	public $incrementing = false;

	const SELF = -2;
	const BLOCKED = -1;
	const STRANGER = 0;
	const FRIEND = 1;
	const FRIEND_REQUEST = 2;
}
