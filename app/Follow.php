<?php

namespace App;

use App\Enums\DbEnums\AccountRole;
use App\Enums\DbEnums\AccountStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Follow extends Model
{
	protected $fillable = ['follower_id', 'owner_id'];
	public $timestamps = false;

	public function follower(): HasOne
	{
		return $this->hasOne(Account::class, 'id', 'follower_id');
	}

	public static function isFollowing(int $followerId, int $ownerId): bool{
		return Follow::where('owner_id', '=', $ownerId)->where('follower_id', '=', $followerId)->exists();
	}
}
