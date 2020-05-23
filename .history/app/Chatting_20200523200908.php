<?php

namespace App;

use App\Enums\DbEnums\AccountRole;
use App\Enums\DbEnums\AccountStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Chatting extends Model
{
	protected $table = 'chatting';
	protected $fillable = ['content', 'info'];
}
