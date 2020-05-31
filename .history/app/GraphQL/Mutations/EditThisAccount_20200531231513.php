<?php

namespace App\GraphQL\Mutations;

use App\Account;
use App\AccountSetting;
use App\Enums\ResultEnums\AccountEditingResultStatus;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\GraphQL\Entities\Input\AccountSettingInput;
use App\GraphQL\Entities\Result\AccountEditingResult;

class EditThisAccount
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

			$this->updateAccountInfo($account, $inputAccount);

			if ($this->updateSetting($account, $setting)) {
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

	protected function updateAccountInfo(Account &$account, $info)
	{
		if (isset($info['name'])) {
			$account->name = $info['name'];
		}
		if (isset($info['avatar_url'])) {
			$account->avatar_url = $info['avatar_url'];
		}
		if (isset($info['describe'])) {
			$account->describe = $info['describe'];
		}
		if (isset($info['birthmonth'])) {
			$account->birthmonth = $info['birthmonth'];
		}
		if (isset($info['birthyear'])) {
			$account->birthyear = $info['birthyear'];
		}
		if (isset($info['phone'])) {
			$account->phone = $info['phone'];
		}
		if (isset($info['email'])) {
			$account->email = $info['email'];
		}
	}

	protected function updateSetting(Account &$account, $setting): bool
	{
		if ($setting) {
			$accountSetting = $account->setting;

			if (!$accountSetting) {
				$accountSetting = new AccountSetting();
				$accountSetting->id = $account->id;
			}

			if ($setting->anonymous !== null) {
				$accountSetting->anonymous = $setting->anonymous;
			}
			if ($setting->birthyear_privacy !== null) {
				$accountSetting->birthyear_privacy = $setting->birthyear_privacy;
			}
			if ($setting->birthmonth_privacy !== null) {
				$accountSetting->birthmonth_privacy = $setting->birthmonth_privacy;
			}
			if ($setting->email_privacy !== null) {
				$accountSetting->email_privacy = $setting->email_privacy;
			}
			if ($setting->phone_privacy !== null) {
				$accountSetting->phone_privacy = $setting->phone_privacy;
			}
			if ($setting->allow_phone_to_search !== null) {
				$accountSetting->allow_phone_to_search = $setting->allow_phone_to_search;
			}
			if ($setting->allow_email_to_search !== null) {
				$accountSetting->allow_email_to_search = $setting->allow_email_to_search;
			}
			if ($setting->not_receive_messages_from_strangers !== null) {
				$accountSetting->not_receive_messages_from_strangers = $setting->not_receive_messages_from_strangers;
			}

			return $accountSetting->save();
		}

		return true;
	}
}
