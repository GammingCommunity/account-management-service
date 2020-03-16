<?php

namespace App\Common;

final class AuthServiceResponseStatus{
	const SUCCESSFUL = "SUCCESSFUL";
	const FAILED = "FAILED";
	const WRONG_PWD = "WRONG_PWD";
	const ACC_NOT_FOUND = "ACC_NOT_FOUND";
	const SESSION_EXPIRED = "SESSION_EXPIRED";
}
