<?php

namespace App\Enums\DbEnums;

final class AccountRelationshipType{
	const BLOCKED = -1;
	const STRANGER = 0;
	const FRIEND = 1;
	const FRIEND_REQUEST = 2;
	const FROM_FRIEND_REQUEST = 3;
}