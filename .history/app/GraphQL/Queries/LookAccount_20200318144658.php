<?php

namespace App\GraphQL\Queries;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\AccountRelationship;
use App\Enums\DbEnums\AccountRelationshipType;
use App\Enums\DbEnums\AccountPrivacyType;
use App\Account;
use App\GraphQL\Entities\Result\AccountLookingResult;

class LookAccount
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
		$ids = $args['ids'];

		if ($rootValue['verified_account']) {
			$currentAccount = $rootValue['verified_account'];

			if ($currentAccount) {
				$this->pushCurrentAccount($currentAccount, $result);
				$this->destroyCurrentAccountId($currentAccount->id, $ids);

				$lookingAccounts = Account::find($ids);

				foreach ($lookingAccounts as $lookingAccount) {
					$accountLookingResult = new AccountLookingResult();

					$this->setDefaultAvatarIfNull($lookingAccount);

					$relasitonship1 = AccountRelationship::where('sender_account_id', $currentAccount->id)->where('receiver_account_id', $lookingAccount->id)->first();
					$relasitonship2 = null;
					if (!$relasitonship1) {
						$relasitonship2 = AccountRelationship::where('sender_account_id', $lookingAccount->id)->where('receiver_account_id', $currentAccount->id)->first();
					}

					$this->handleBlockedAccount($lookingAccount, $relasitonship1, $relasitonship2, $accountLookingResult);
					$this->handleFriendAccount($lookingAccount, $relasitonship1, $relasitonship2, $accountLookingResult);
					$this->handleStrangerAccount($lookingAccount, $relasitonship1, $relasitonship2, $accountLookingResult);
					
					$lookingAccount->login_name = null;
					$lookingAccount->account_setting_id = null;
					$lookingAccount->updated_at = null;
					
					$accountLookingResult->account = $lookingAccount;

					array_push($result, $accountLookingResult);
				}
			}
		}

		return $result;
	}

	protected function destroyCurrentAccountId(int $id, array &$ids)
	{
		$index = array_search($id, $ids);
		if ($index !== false) {
			unset($ids[$index]);
		}
	}

	protected function pushCurrentAccount(Account $currentAccount, array &$result)
	{
		$accountLookingResult = new AccountLookingResult();

		$this->setDefaultAvatarIfNull($currentAccount);
		$accountLookingResult->account = $currentAccount;
		$accountLookingResult->relationship = AccountRelationshipType::SELF;

		array_push($result, $accountLookingResult);
	}

	protected function handleBlockedAccount(Account &$lookingAccount, ?AccountRelationship $relasitonship1, ?AccountRelationship $relasitonship2, AccountLookingResult &$accountLookingResult)
	{
		if ($accountLookingResult->relationship !== null) {
			return;
		}
		if (
			($relasitonship1 && $relasitonship1->relationship_type === AccountRelationshipType::BLOCKED) ||
			($relasitonship2 && $relasitonship2->relationship_type === AccountRelationshipType::BLOCKED)
		) {
			// blocked account
			$accountLookingResult->relationship = AccountRelationshipType::BLOCKED;
			$lookingAccount = null;
		}
	}

	protected function handleFriendAccount(Account &$lookingAccount, ?AccountRelationship $relasitonship1, ?AccountRelationship $relasitonship2, AccountLookingResult &$accountLookingResult)
	{
		if ($accountLookingResult->relationship !== null) {
			return;
		}
		if (
			($relasitonship1 && $relasitonship1->relationship_type === AccountRelationshipType::FRIEND) ||
			($relasitonship2 && $relasitonship2->relationship_type === AccountRelationshipType::FRIEND)
		) {
			// friend account
			$accountLookingResult->relationship = AccountRelationshipType::FRIEND;

			$lookingAccount->setting;
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

	protected function handleStrangerAccount(Account &$lookingAccount, ?AccountRelationship $relasitonship1, ?AccountRelationship $relasitonship2, AccountLookingResult &$accountLookingResult)
	{
		if ($accountLookingResult->relationship !== null) {
			return;
		}
		if (!$relasitonship1 && !$relasitonship2) {
			//	stranger account
			$accountLookingResult->relationship = AccountRelationshipType::STRANGER;

			$lookingAccount->setting;
			dd($lookingAccount);
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

	protected function setDefaultAvatarIfNull(Account &$account)
	{
		if ($account->avatar_url == null) {
			$account->avatar_url = config('default.account_avatar');
		}
	}
}
