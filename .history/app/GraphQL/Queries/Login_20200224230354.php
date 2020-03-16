<?php

namespace App\GraphQL\Queries;

use App\Account;
use App\GraphQL\Entities\Result\LoggingResult;
use GraphQL\Type\Definition\ResolveInfo;
use App\Enums\ResultEnums\LoggingResultStatus;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\GraphQL\Entities\Result\ErrorResult;
use GuzzleHttp\Client;
use App\Common\AuthServiceResponse;
use App\Common\AuthServiceResponseStatus;

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

		$res = (new Client())->request('POST', env('AUTH_SERVICE_URL') . '/login', [
			'headers' => ['token' => $token]
		]);
		if ($res->getStatusCode() === 200) {
			$resData = new AuthServiceResponse($res->getBody()->getContents());
			if ($resData->status === AuthServiceResponseStatus::SUCCESSFUL) {
				return Account::find((new AuthServiceJwtPayload($token))->accountId);
			} else {
				ErrorResult::exit(json_encode([
					'status' => $resData->status,
					'describe' => $resData->describe
				], JSON_PRETTY_PRINT));
			}
		} else {
			ErrorResult::exit('Status code: ' . $res->getStatusCode());
		}
		
		// $account = Account::where('login_name', $args['username'])->first();

		// if ($account && $args['pwd']) {
		// 	if (password_verify($args['pwd'], $account->password)) {
		// 		$jwtToken = JsonWebToken::encode($account->id);
		// 		$result->status = LoggingResultStatus::SUCCESS;
		// 		$result->token = $jwtToken;
		// 		$result->account = $account;
		// 	} else {
		// 		$result->status = LoggingResultStatus::WRONG_PWD;
		// 	}
		// }

		return $result;
	}
}
