<?php

namespace App\Enums\DbEnums;

final class AccountRelationshipType{
	const SELF = -2;
	const BLOCKED = -1;
	const STRANGER = 0;
	const FRIEND = 1;
	const FRIEND_REQUEST = 2;
}