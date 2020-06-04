<?php

namespace App\GraphQL\Queries;

use App\Account;
use App\GraphQL\Entities\Result\LoggingResult;
use GraphQL\Type\Definition\ResolveInfo;
use App\Enums\ResultEnums\LoggingResultStatus;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Common\AuthService\AuthServiceConnection;
use App\Common\AuthService\AuthServiceJwtPayload;
use App\Common\AuthService\AuthServiceResponseStatus;
use App\Common\Helpers\AccountHelper;

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
		$result = new LoggingResult();
		$authServiceResponse = AuthServiceConnection::request('POST', '/login', [
			'headers' => [
				'secret_key' => env('SECRET_KEY')
			],
			'form_params' => [
				'username' => $args['username'],
				'pwd' => $args['pwd']
			]
		]);

		if ($authServiceResponse->status === AuthServiceResponseStatus::SUCCESSFUL) {
			$result->status = LoggingResultStatus::SUCCESS;
			$result->token = $authServiceResponse->data;

			$jwtPayload = new AuthServiceJwtPayload($authServiceResponse->data);
			$result->account = Account::find($jwtPayload->accountId);
		} else if ($authServiceResponse->status === AuthServiceResponseStatus::WRONG_USERNAME) {
			$result->status = LoggingResultStatus::WRONG_USERNAME;
		} else if ($authServiceResponse->status === AuthServiceResponseStatus::WRONG_PWD) {
			$result->status = LoggingResultStatus::WRONG_PWD;
		} else {
			$result->describe = [
				"status -> {$authServiceResponse->status}",
				"describe -> {$authServiceResponse->describe}"
			];
		}

		return $result;
	}
}
