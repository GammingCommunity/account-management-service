<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OAuthProvider extends Model
{
	protected $table = 'oauth_provider';
	public $timestamps = false;
}
