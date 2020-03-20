<?php

namespace App\GraphQL\Mutations;

use App\Account;
use App\AccountInfoBirthMonth;
use App\AccountInfoBirthYear;
use App\AccountInfoEmail;
use App\AccountInfoPhone;
use App\AccountSetting;
use App\Enums\ResultEnums\AccountEditingResultStatus;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\GraphQL\Entities\Input\AccountSettingInput;
use App\GraphQL\Entities\Result\AccountEditingResult;

class EditAccount
{
	/**
	 * Return a value for the field.
	 *
	 * @param  null  $rootValue Usually contains the result returned from the parent field. In this case, it is always `null`.
	 * @param  mixed[]  $args The arguments that were passed into the field.
	 * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext  $context Arbitrary data that is shared between all fields of a single query.
	 * @param  \GraphQL\Type\Definition\ResolveInfo  $resolveInfo Information about the query itself, such as the execution state, the field name, path to the field from the root, and more.
	 * @return AccountEditingResult
	 */
	public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): AccountEditingResult
	{
		$result = new AccountEditingResult();
		$account = $rootValue['verified_account'];
		$inputAccount = $args['account'];

		if ($account) {

			$setting = isset($inputAccount['setting']) ? new AccountSettingInput($args) : null;

			if (isset($inputAccount['name'])) {
				$account->name = $inputAccount['name'];
			}
			if (isset($inputAccount['avatar_url'])) {
				$account->avatar_url = $inputAccount['avatar_url'];
			}
			if (isset($inputAccount['describe'])) {
				$account->describe = $inputAccount['describe'];
			}
			if (isset($inputAccount['birthmonth'])) {
				$account->birthmonth = $inputAccount['birthmonth'];
			}
			if (isset($inputAccount['birthyear'])) {
				$account->birthyear = $inputAccount['birthyear'];
			}
			if (isset($inputAccount['phone'])) {
				$account->phone = $inputAccount['phone'];
			}
			if (isset($inputAccount['email'])) {
				$account->email = $inputAccount['email'];
			}

			if($this->updateSetting($account, $setting, $result)){
				if ($account->save()) {
					$result->status = AccountEditingResultStatus::SUCCESS;
				} else {
					$result->describe = 'Unable to save the Account.';
				}
			} else {
				$result->describe = 'Unable to update the Setting.';
			}
		} else {
			$result->status = AccountEditingResultStatus::ACC_NOT_FOUND;
		}

		return $result;
	}


	protected function updateSetting(Account &$account, $setting, AccountEditingResult &$accountEditingResult): bool
	{
		if ($setting) {
			$accountSetting = $account->setting;

			if (!$accountSetting) {
				$accountSetting = new AccountSetting();
			}

			if($setting->anonymous !== null){
				$accountSetting->anonymous = $setting->anonymous;
			}
			if($setting->birthyear_privacy !== null){
				$accountSetting->birthyear_privacy = $setting->birthyear_privacy;
			}
			if($setting->birthmonth_privacy !== null){
				$accountSetting->birthmonth_privacy = $setting->birthmonth_privacy;
			}
			if($setting->email_privacy !== null){
				$accountSetting->email_privacy = $setting->email_privacy;
			}
			if($setting->phone_privacy !== null){
				$accountSetting->phone_privacy = $setting->phone_privacy;
			}

			if (!$accountSetting->save()) {
				$accountEditingResult->describe = 'Unable to save account setting.';
				return false;
			} else {
				return true;
			}
		}

		return true;
	}
}
