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

		$acc = Account::where('login_name', $inputAccount['login_name'])->first(['id']);
		if ($acc) {
			$result->status = AccountRegistrationResultStatus::NAMESAKE;
		} else {
			$accountSetting = AccountSetting::createDefaultSetting();

			if ($accountSetting) {
				$createdAccount = Account::create([
					'login_name' => $inputAccount['login_name'],
					'name' => $inputAccount['name'],
					'describe' => $inputAccount['describe'] ?? '',
					'password' => password_hash($inputAccount['password'], PASSWORD_BCRYPT, ['cost' => 10]),
					'account_setting_id' => $accountSetting->id,
				]);

				if ($createdAccount) {
					$createdAccount = Account::find($createdAccount->id);

					if($createdAccount){
						$authServiceResponse = AuthServiceConnection::request('POST', '/register', [
							'headers' => [
								'secret_key' => env('SECRET_KEY'),
							],
							'form_params' => [
								'username' => $createdAccount->login_name,
								'pwd' => $createdAccount->password,
								'id' => $createdAccount->id,
								'role' => $createdAccount->role,
								'status' => $createdAccount->status
							]
						]);
	
						if($authServiceResponse->status === AuthServiceResponseStatus::SUCCESSFUL){
							$result->status = AccountRegistrationResultStatus::SUCCESS;
							$result->token = $authServiceResponse->data;
							$result->account = $createdAccount;
						} else {
							$result->describe = json_encode([
								'status' => $authServiceResponse->status,
								'describe' => $authServiceResponse->describe
							], JSON_PRETTY_PRINT);
						}
					} else {
						$result->describe = '*Unable to select account.';
					}
				} else {
					$result->describe = 'Unable to create account.';
					//must delete account setting if unable to create account
					$accountSetting->delete();
				}
			} else {
				$result->describe = 'Unable to create account setting.';
			}
		}

		return $result;
	}
}
