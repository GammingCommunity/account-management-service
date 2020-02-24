<?php

namespace App\Common\AuthService;

final class AuthServiceResponseStatus{
	const SUCCESSFUL = "SUCCESSFUL";
	const FAILED = "FAILED";
	const WRONG_PWD = "WRONG_PWD";
	const WRONG_USERNAME = "WRONG_USERNAME";
	const SESSION_EXPIRED = "SESSION_EXPIRED";
}
