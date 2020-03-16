<?php

namespace App\GraphQL\Queries;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\AccountRelationship;
use App\Enums\DbEnums\AccountRelationshipType;
use App\Enums\DbEnums\AccountPrivacyType;
use App\Account;
use App\Enums\ResultEnums\AccountLookingResultStatus;
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
				if (!$ids) {
					$accountLookingResult = new AccountLookingResult();
					$this->setDefaultAvatarIfNull($currentAccount);
					$accountLookingResult->account = $currentAccount;
					$accountLookingResult->relationship = AccountRelationshipType::SELF;
					$accountLookingResult->status = AccountLookingResultStatus::SUCCESS;

					array_push($result, $accountLookingResult);
				} else {
					foreach ($ids as $value) {
						# code...
					}
				}
			}
		}

		return $result;
	}

	protected function handleBlockedAccount(Account &$lookingAccount, ?AccountRelationship $relasitonship1, ?AccountRelationship $relasitonship2, AccountLookingResult &$accountLookingResult)
	{
		if (
			($relasitonship1 && $relasitonship1->account_relationship_type_id === AccountRelationshipType::BLOCKED) ||
			($relasitonship2 && $relasitonship2->account_relationship_type_id === AccountRelationshipType::BLOCKED)
		) {
			// blocked account
			$accountLookingResult->relationship = AccountRelationshipType::BLOCKED;
			$lookingAccount = null;
		}
	}

	protected function handleFriendAccount(Account &$lookingAccount, ?AccountRelationship $relasitonship1, ?AccountRelationship $relasitonship2, AccountLookingResult &$accountLookingResult)
	{
		if (
			($relasitonship1 && $relasitonship1->account_relationship_type_id === AccountRelationshipType::FRIEND) ||
			($relasitonship2 && $relasitonship2->account_relationship_type_id === AccountRelationshipType::FRIEND)
		) {
			// friend account
			$accountLookingResult->relationship = AccountRelationshipType::FRIEND;
			if ($lookingAccount->birthMonth) {
				if (
					$lookingAccount->birthMonth->account_privacy_type_id === AccountPrivacyType::PRIVATE
				) {
					$lookingAccount->account_info_birth_month_id = null;
				}
			}
			if ($lookingAccount->birthYear) {
				if (
					$lookingAccount->birthYear->account_privacy_type_id === AccountPrivacyType::PRIVATE
				) {
					$lookingAccount->account_info_birth_year_id = null;
				}
			}
			if ($lookingAccount->phone) {
				if (
					$lookingAccount->phone->account_privacy_type_id === AccountPrivacyType::PRIVATE
				) {
					$lookingAccount->account_info_phone_id = null;
				}
			}
			if ($lookingAccount->email) {
				if (
					$lookingAccount->email->account_privacy_type_id === AccountPrivacyType::PRIVATE
				) {
					$lookingAccount->account_info_email_id = null;
				}
			}
		}
	}

	protected function handleStrangerAccount(Account &$lookingAccount, ?AccountRelationship $relasitonship1, ?AccountRelationship $relasitonship2, AccountLookingResult &$accountLookingResult)
	{
		if (!$relasitonship1 && !$relasitonship2) {
			//	stranger account
			$accountLookingResult->relationship = AccountRelationshipType::STRANGER;
			if ($lookingAccount->birthMonth) {
				if (
					$lookingAccount->birthMonth->account_privacy_type_id !== AccountPrivacyType::PUBLIC
				) {
					$lookingAccount->account_info_birth_month_id = null;
				}
			}
			if ($lookingAccount->birthYear) {
				if (
					$lookingAccount->birthYear->account_privacy_type_id !== AccountPrivacyType::PUBLIC
				) {
					$lookingAccount->account_info_birth_year_id = null;
				}
			}
			if ($lookingAccount->phone) {
				if (
					$lookingAccount->phone->account_privacy_type_id !== AccountPrivacyType::PUBLIC
				) {
					$lookingAccount->account_info_phone_id = null;
				}
			}
			if ($lookingAccount->email) {
				if (
					$lookingAccount->email->account_privacy_type_id !== AccountPrivacyType::PUBLIC
				) {
					$lookingAccount->account_info_email_id = null;
				}
			}
		}
	}

	protected function setDefaultAvatarIfNull(Account &$account)
	{
		if (!$account->avatar_url) {
			$account->avatar_url = config('account_avatar');
		}
	}
}
