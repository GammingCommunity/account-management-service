<?php

namespace App\GraphQL\Queries;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\AccountRelationship;
use App\AccountRelationshipType;
use App\AccountPrivacyType;
use App\Account;
use App\Enums\AccountLookingResultStatus;
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
	 * @return AccountLookingResult
	 */
	public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): AccountLookingResult
	{
		$result = new AccountLookingResult();

		if ($rootValue['verified_account']) {
			$lookingAccountId = $args['id'] ?? $rootValue['verified_account']->id;
			$account = $rootValue['verified_account'];

			if ($account) {
				if ($account->id === $lookingAccountId) {
					$result->account = $account;
					$result->relationship = AccountRelationshipType::SELF;
				} else {
					$lookingAccount = Account::find($lookingAccountId);
					if ($lookingAccount) {
						$relasitonship1 = AccountRelationship::where('sender_account_id', $account->id)->where('receiver_account_id', $lookingAccount->id)->first();
						$relasitonship2 = null;
						if (!$relasitonship1) {
							$relasitonship2 = AccountRelationship::where('sender_account_id', $lookingAccount->id)->where('receiver_account_id', $account->id)->first();
						}

						$lookingAccount->login_name = null;
						$lookingAccount->account_setting_id = null;
						$lookingAccount->account_role_id = null;
						$lookingAccount->updated_at = null;

						if (
							($relasitonship1 && $relasitonship1->account_relationship_type_id === AccountRelationshipType::BLOCKED) ||
							($relasitonship2 && $relasitonship2->account_relationship_type_id === AccountRelationshipType::BLOCKED)
						) {
							// blocked account
							$result->relationship = AccountRelationshipType::BLOCKED;
							$lookingAccount = null;
						} else if (
							($relasitonship1 && $relasitonship1->account_relationship_type_id === AccountRelationshipType::FRIEND) ||
							($relasitonship2 && $relasitonship2->account_relationship_type_id === AccountRelationshipType::FRIEND)
						) {
							// friend account
							$result->relationship = AccountRelationshipType::FRIEND;
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
						} else {
							//	stranger account
							$result->relationship = AccountRelationshipType::STRANGER;
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

						$result->account = $lookingAccount;
						$result->status = AccountLookingResultStatus::SUCCESS;
					} else {
						$result->status = AccountLookingResultStatus::ACC_NOT_FOUND;
					}
				}
			}
		}

		return $result;
	}
}
