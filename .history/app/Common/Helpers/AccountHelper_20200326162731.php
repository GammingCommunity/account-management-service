<?php

namespace App\Common\Helpers;

use App\Account;

class AccountHelper{
	static public function setDefaultAvatarIfNull(Account &$account)
	{
		if ($account->avatar_url == null) {
			$account->avatar_url = config('default.account_avatar');
		}
	}
}

