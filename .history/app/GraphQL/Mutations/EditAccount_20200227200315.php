<?php

namespace App\GraphQL\Mutations;

use App\Account;
use App\AccountInfoBirthMonth;
use App\AccountInfoBirthYear;
use App\AccountInfoEmail;
use App\AccountInfoPhone;
use App\Enums\ResultEnums\AccountEditingResultStatus;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\GraphQL\Entities\Input\AccountBirthMonthInput;
use App\GraphQL\Entities\Input\AccountBirthYearInput;
use App\GraphQL\Entities\Input\AccountEmailInput;
use App\GraphQL\Entities\Input\AccountPhoneInput;
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
			$booleanResult = true;

			$birthMonth = isset($inputAccount['birth_month']) ? new AccountBirthMonthInput($args) : null;
			$birthYear = isset($inputAccount['birth_year']) ? new AccountBirthYearInput($args) : null;
			$phone = isset($inputAccount['phone']) ? new AccountPhoneInput($args) : null;
			$email = isset($inputAccount['email']) ? new AccountEmailInput($args) : null;
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

			$booleanResult &= $this->updateBirthMonth($account, $birthMonth, $result);
			$booleanResult &= $this->updateBirthYear($account, $birthMonth, $result);

			if ($email) {
				if ($account->email) {
					$account->email->email = $email->email;
					$account->email->privacy_type = $email->privacyType;
					if (!$account->email->save()) {
						$result->describe = 'Unable to save account email.';
					}
				} else {
					$newEmail = AccountInfoEmail::create([
						'email' => $email->email,
						'privacy_type' => $email->privacyType
					]);
					if ($newEmail) {
						$account->account_info_email_id = $newEmail->id;
					} else {
						$result->describe = 'Unable to create account email.';
					}
				}
			}
			if ($phone) {
				if ($account->phone) {
					$account->phone->phone = $phone->phone;
					$account->phone->privacy_type = $phone->privacyType;
					if (!$account->phone->save()) {
						$result->describe = 'Unable to save account phone.';
					}
				} else {
					$newPhone = AccountInfoPhone::create([
						'phone' => $phone->phone,
						'privacy_type' => $phone->privacyType
					]);
					if ($newPhone) {
						$account->account_info_phone_id = $newPhone->id;
					} else {
						$result->describe = 'Unable to create account phone.';
					}
				}
			}
			if ($setting) {
				if ($account->setting) {
					$account->setting->anonymous = $setting->anonymous;
					if (!$account->setting->save()) {
						$result->describe = 'Unable to save account setting.';
					}
				} else {
					$result->describe = 'The account setting does not exist.';
				}
			}

			if ($account->save()) {
				$result->status = AccountEditingResultStatus::SUCCESS;
			} else {
				$result->describe = 'Unable to save account.';
				//must delete ralationship with account table if it unable to save
				if (isset($newBirthMonth) && $newBirthMonth) {
					$newBirthMonth->delete();
				}
				if (isset($newBirthYear) && $newBirthYear) {
					$newBirthYear->delete();
				}
				if (isset($newPhone) && $newPhone) {
					$newPhone->delete();
				}
				if (isset($newEmail) && $newEmail) {
					$newEmail->delete();
				}
			}

			if ($booleanResult) {
				$result->status = AccountEditingResultStatus::SUCCESS;
			}
		} else {
			$result->status = AccountEditingResultStatus::ACC_NOT_FOUND;
		}

		return $result;
	}

	protected function updateBirthMonth(Account &$account, ?string $birthMonth, AccountEditingResult &$accountEditingResult): bool
	{
		$booleanResult = true;

		if ($birthMonth) {
			if ($account->birthMonth) {
				$account->birthMonth->month = $birthMonth->month;
				$account->birthMonth->privacy_type = $birthMonth->privacyType;

				$booleanResult = $account->birthMonth->save();
				if (!$booleanResult) {
					$accountEditingResult->describe = 'Unable to save account birth month.';
				}
			} else {
				$newBirthMonth = AccountInfoBirthMonth::create([
					'month' => $birthMonth->month,
					'privacy_type' => $birthMonth->privacyType
				]);
				if ($newBirthMonth) {
					$account->account_info_birth_month_id = $newBirthMonth->id;
				} else {
					$booleanResult = false;
					$accountEditingResult->describe = 'Unable to create account birth month.';
				}
			}
		}

		return $booleanResult;
	}

	protected function updateBirthYear(Account &$account, ?string $birthYear, AccountEditingResult &$accountEditingResult): bool
	{
		$booleanResult = true;

		if ($birthYear) {
			if ($account->birthYear) {
				$account->birthYear->year = $birthYear->year;
				$account->birthYear->privacy_type = $birthYear->privacyType;

				$booleanResult = $account->birthYear->save();
				if (!$booleanResult) {
					$accountEditingResult->describe = 'Unable to save account birth year.';
				}
			} else {
				$newBirthYear = AccountInfoBirthYear::create([
					'year' => $birthYear->year,
					'privacy_type' => $birthYear->privacyType
				]);

				if ($newBirthYear) {
					$booleanResult = true;
					$account->account_info_birth_year_id = $newBirthYear->id;
				} else {
					$booleanResult = false;
					$accountEditingResult->describe = 'Unable to create account birth year.';
				}
			}
		}

		return $booleanResult;
	}
}
