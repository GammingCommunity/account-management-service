<?php

namespace App\Enums\ResultEnums;

use App\Enums\ResultEnums\AncestorResultEnum;

final class LoggingResultStatus extends AncestorResultEnum{
	const WRONG_USERNAME = 1;
	const WRONG_PWD = 2;
}