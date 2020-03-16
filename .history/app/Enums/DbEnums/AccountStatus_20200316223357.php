<?php

namespace App\Enums\DbEnums;

final class AccountStatus{
	public BANNED = config('account.banned');
	const UNACTIVATED = config('account.unactivated');
	const ACTIVATED = config('account.activated');
}