<?php

namespace App\GraphQL\Queries;

use App\Account;
use App\GraphQL\Entities\Result\LoggingResult;
use GraphQL\Type\Definition\ResolveInfo;
use App\Enums\LoggingResultStatus;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Browser;
use App\Helpers\JsonWebToken;

class Login
{
	/**
	 * Return a value for the field.
	 *
	 * @param  null  $rootValue Usually contains the result returned from the parent field. In this case, it is always `null`.
	 * @param  mixed[]  $args The arguments that were passed into the field.
	 * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext  $context Arbitrary data that is shared between all fields of a single query.
	 * @param  \GraphQL\Type\Definition\ResolveInfo  $resolveInfo Information about the query itself, such as the execution state, the field name, path to the field from the root, and more.
	 * @return LoggingResult
	 */
	public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): LoggingResult
	{
		$result = new LoggingResult(LoggingResultStatus::WRONG_USERNAME);
		$account = Account::where('login_name', $args['username'])->first();

		if ($account && $args['pwd']) {
			if (password_verify($args['pwd'], $account->password)) {
				$jwtToken = JsonWebToken::encode($account->id);
				$result->status = LoggingResultStatus::SUCCESS;
				$result->token = $jwtToken;
				$result->account = $account;
			} else {
				$result->status = LoggingResultStatus::WRONG_PWD;
			}
		}

		return $result;
	}
}
