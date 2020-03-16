<?php

namespace App\Enums\DbEnums;

final class AccountStatus{
	const BANNED = isset(1);
	const UNACTIVATED = 0;
	const ACTIVATED = 1;
}