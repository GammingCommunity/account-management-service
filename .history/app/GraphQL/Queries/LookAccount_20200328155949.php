<?php

namespace App\GraphQL\Queries;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\AccountRelationship;
use App\Enums\DbEnums\AccountRelationshipType;
use App\Enums\DbEnums\AccountPrivacyType;
use App\Account;
use App\Common\Helpers\AccountHelper;
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
		$currentAccount = $rootValue['verified_account'];

		if ($currentAccount) {
			$lookingAccounts = Account::find($ids);

			foreach ($lookingAccounts as $lookingAccount) {
				$accountLookingResult = new AccountLookingResult();

				AccountHelper::setDefaultAvatarIfNull($lookingAccount);

				$relasitonship = AccountRelationship::where(function ($query) use ($lookingAccount, $currentAccount) {
					return $query->where('sender_account_id', $currentAccount->id)->where('receiver_account_id', $lookingAccount->id);
				})->orWhere(function ($query) use ($lookingAccount, $currentAccount) {
					return $query->where('sender_account_id', $lookingAccount->id)->where('receiver_account_id', $currentAccount->id);
				})->first(['relationship_type']);

				$this->handleBlockedAccount($lookingAccount, $relasitonship, $accountLookingResult);
				if ($accountLookingResult->relationship === null) {
					$this->handleFriendAccount($lookingAccount, $relasitonship, $accountLookingResult);
				}
				if ($accountLookingResult->relationship === null) {
					$this->handleStrangerAccount($lookingAccount, $relasitonship, $accountLookingResult);
				}

				$accountLookingResult->account = $lookingAccount;

				array_push($result, $accountLookingResult);
			}
		}

		return $result;
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
		if (!$relasitonship) {
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
		} else  ($relasitonship->relationship_type === AccountRelationshipType::FRIEND_REQUEST) {
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
