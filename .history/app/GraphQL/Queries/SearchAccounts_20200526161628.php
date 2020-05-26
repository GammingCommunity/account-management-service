<?php

namespace App\GraphQL\Queries;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\AccountRelationship;
use App\Enums\DbEnums\AccountRelationshipType;
use App\Enums\DbEnums\AccountPrivacyType;
use App\Account;
use App\AccountSetting;
use App\Common\Helpers\AccountHelper;
use App\GraphQL\Entities\Result\AccountLookingResult;
use Illuminate\Database\Eloquent\Collection;

class SearchAccounts
{
	/**
	 * Return a value for the field.
	 *
	 * @param  null  $rootValue Usually contains the result returned from the parent field. In this case, it is always `null`.
	 * @param  mixed[]  $args The arguments that were passed into the field.
	 * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext  $context Arbitrary data that is shared between all fields of a single query.
	 * @param  \GraphQL\Type\Definition\ResolveInfo  $resolveInfo Information about the query itself, such as the execution state, the field name, path to the field from the root, and more.
	 * @return array AccountLookingResult[]
	 */
	public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): array
	{

		$result = [];
		$searchKey = $args['key'];
		$currentAccount = $rootValue['verified_account'];

		if ($currentAccount) {
			$lookingAccounts = $this->findAccounts($currentAccount->id, $searchKey);

			foreach ($lookingAccounts as $lookingAccount) {
				$accountLookingResult = new AccountLookingResult();

				AccountHelper::setDefaultAvatarIfNull($lookingAccount);

				$relationship = AccountRelationship::where(function ($query) use ($lookingAccount, $currentAccount) {
					return $query->where('sender_account_id', $currentAccount->id)->where('receiver_account_id', $lookingAccount->id);
				})->orWhere(function ($query) use ($lookingAccount, $currentAccount) {
					return $query->where('sender_account_id', $lookingAccount->id)->where('receiver_account_id', $currentAccount->id);
				})->first(['relationship_type', 'sender_account_id', 'receiver_account_id']);

				$lookingAccount->setting = $this->createAccountSettingIfItNotExist($lookingAccount);
				$this->handleBlockedAccount($lookingAccount, $relationship, $accountLookingResult);
				if ($accountLookingResult->relationship === null) {
					$this->handleFriendAccount($lookingAccount, $relationship, $accountLookingResult);
				}
				if ($accountLookingResult->relationship === null) {
					$this->handleStrangerAccount($lookingAccount, $relationship, $accountLookingResult);
				}

				$accountLookingResult->account = $lookingAccount;

				array_push($result, $accountLookingResult);
			}
		}

		return $result;
	}

	protected function createAccountSettingIfItNotExist(Account $account): AccountSetting
	{
		if ($account->setting) {
			return $account->setting;
		} else {
			return AccountSetting::createModel($account->id);
		}
	}

	protected function findAccounts(int $currentAccountId, string $key): array
	{
		$accounts = [];

		foreach ($this->findAccountsByString($currentAccountId, $key) as $account) {
			array_push($accounts, $account);
		}

		return $accounts;
	}

	protected function findAccountsByString(int $currentAccountId, $key): Collection
	{
		return Account::whereRaw("`id` != {$currentAccountId} and (CONVERT(`id`, CHAR) = '{$key}' or UPPER(`describe`) like UPPER('%{$key}%') or UPPER(`name`) like UPPER('%{$key}%'))")->get();
	}

	protected function handleBlockedAccount(Account &$lookingAccount, ?AccountRelationship $relasitonship, AccountLookingResult &$accountLookingResult)
	{
		if (
			$relasitonship && $relasitonship->relationship_type === AccountRelationshipType::BLOCKED
		) {
			// blocked account
			$accountLookingResult->relationship = AccountRelationshipType::BLOCKED;
			$lookingAccount = null;
		}
	}

	protected function handleFriendAccount(Account &$lookingAccount, ?AccountRelationship $relasitonship, AccountLookingResult &$accountLookingResult)
	{
		if (
			$relasitonship && $relasitonship->relationship_type === AccountRelationshipType::FRIEND
		) {
			// friend account
			$accountLookingResult->relationship = AccountRelationshipType::FRIEND;

			if ($lookingAccount->setting->birthmonth_privacy === AccountPrivacyType::PRIVATE) {
				$lookingAccount->birthmonth = null;
			}
			if ($lookingAccount->setting->birthyear_privacy === AccountPrivacyType::PRIVATE) {
				$lookingAccount->birthyear = null;
			}
			if ($lookingAccount->setting->email_privacy === AccountPrivacyType::PRIVATE) {
				$lookingAccount->email = null;
			}
			if ($lookingAccount->setting->phone_privacy === AccountPrivacyType::PRIVATE) {
				$lookingAccount->phone = null;
			}
		}
	}

	protected function handleStrangerAccount(Account &$lookingAccount, ?AccountRelationship $relasitonship, AccountLookingResult &$accountLookingResult)
	{
		if ($relasitonship && $relasitonship->relationship_type === AccountRelationshipType::FRIEND_REQUEST) {
			//	stranger account
			if($relasitonship->sender_account_id === $lookingAccount->id){
				$accountLookingResult->relationship = AccountRelationshipType::FROM_FRIEND_REQUEST;
			} else {
				$accountLookingResult->relationship = AccountRelationshipType::FRIEND_REQUEST;
			}

			if ($lookingAccount->setting->birthmonth_privacy !== AccountPrivacyType::PUBLIC) {
				$lookingAccount->birthmonth = null;
			}
			if ($lookingAccount->setting->birthyear_privacy !== AccountPrivacyType::PUBLIC) {
				$lookingAccount->birthyear = null;
			}
			if ($lookingAccount->setting->email_privacy !== AccountPrivacyType::PUBLIC) {
				$lookingAccount->email = null;
			}
			if ($lookingAccount->setting->phone_privacy !== AccountPrivacyType::PUBLIC) {
				$lookingAccount->phone = null;
			}
		} else {
			//	stranger account
			$accountLookingResult->relationship = AccountRelationshipType::STRANGER;

			if ($lookingAccount->setting->birthmonth_privacy !== AccountPrivacyType::PUBLIC) {
				$lookingAccount->birthmonth = null;
			}
			if ($lookingAccount->setting->birthyear_privacy !== AccountPrivacyType::PUBLIC) {
				$lookingAccount->birthyear = null;
			}
			if ($lookingAccount->setting->email_privacy !== AccountPrivacyType::PUBLIC) {
				$lookingAccount->email = null;
			}
			if ($lookingAccount->setting->phone_privacy !== AccountPrivacyType::PUBLIC) {
				$lookingAccount->phone = null;
			}
		}
	}
}
