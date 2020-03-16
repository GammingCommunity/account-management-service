<?php 

return [
	// account status
	'banned' => -1,
	'unactivated' => 0,
	'activated' => 1,

	// account role
	'user' => 1,
	'admin' => 2,
	'master' => 3,

	// account relationship type
	'self' => -2,
	'blocked' => -1,
	'stranger' => 0,
	'friend' => 1,
	'friend_request' => 2,

	// account privacy type
	'private' => -1,
	'public' => 1,
	'friend' => 2,
];