<?php

namespace App\Enums;

use App\Enums\AncestorResultEnum;

final class LoggingResultStatus extends AncestorResultEnum{
	const WRONG_USERNAME = 1;
	const WRONG_PWD = 2;
}