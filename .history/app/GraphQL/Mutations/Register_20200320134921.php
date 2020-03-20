<?php

namespace App\GraphQL\Mutations;

use App\Account;
use App\AccountSetting;
use App\Common\AuthService\AuthServiceConnection;
use App\Common\AuthService\AuthServiceResponseStatus;
use App\Enums\ResultEnums\AccountRegistrationResultStatus;
use App\GraphQL\Entities\Result\AccountRegistrationResult;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class Register
{
	/**
	 * Return a value for the field.
	 *
	 * @param  null  $rootValue Usually contains the result returned from the parent field. In this case, it is always `null`.
	 * @param  mixed[]  $args The arguments that were passed into the field.
	 * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext  $context Arbitrary data that is shared between all fields of a single query.
	 * @param  \GraphQL\Type\Definition\ResolveInfo  $resolveInfo Information about the query itself, such as the execution state, the field name, path to the field from the root, and more.
	 * @return AccountRegistrationResult
	 */
	public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): AccountRegistrationResult
	{
		$result = new AccountRegistrationResult();
		$inputAccount = $args['account'];

		$createdAccount = Account::createNew($inputAccount['name'], $inputAccount['describe'] ?? '');
		dd($createdAccount);

		$authServiceResponse = AuthServiceConnection::request('POST', '/register', [
			'headers' => [
				'secret_key' => env('PRIVATE_KEY'),
			],
			'form_params' => [
				'username' => $inputAccount['login_name'],
				'pwd' => $inputAccount['password'],
				'id' => $createdAccount->id,
				'role' => $createdAccount->role,
				'status' => $createdAccount->status
			]
		]);

		if ($authServiceResponse->status === AuthServiceResponseStatus::SUCCESSFUL) {
			$result->status = AccountRegistrationResultStatus::SUCCESS;
			$result->token = $authServiceResponse->data;
			$this->setDefaultAvatarIfNull($createdAccount);
			$result->account = $createdAccount;
		} else {
			$result->describe = [
				"author -> authService",
				"status -> {$authServiceResponse->status}",
				"describe -> {$authServiceResponse->describe}"
			];

			$createdAccount->delete();
		}

		return $result;
	}

	protected function setDefaultAvatarIfNull(Account &$account)
	{
		if ($account->avatar_url == null) {
			$account->avatar_url = config('default.account_avatar');
		}
	}
}
