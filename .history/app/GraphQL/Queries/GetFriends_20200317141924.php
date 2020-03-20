<?php

namespace App\GraphQL\Queries;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\AccountRelationship;
use App\Enums\DbEnums\AccountRelationshipType;
use App\Enums\DbEnums\AccountPrivacyType;
use App\Account;
use App\GraphQL\Entities\Result\FriendResult;

class GetFriends
{
	/**
	 * Return a value for the field.
	 *
	 * @param  null  $rootValue Usually contains the result returned from the parent field. In this case, it is always `null`.
	 * @param  mixed[]  $args The arguments that were passed into the field.
	 * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext  $context Arbitrary data that is shared between all fields of a single query.
	 * @param  \GraphQL\Type\Definition\ResolveInfo  $resolveInfo Information about the query itself, such as the execution state, the field name, path to the field from the root, and more.
	 * @return array FriendResult[]
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

					$lookingAccount->login_name = null;
					$lookingAccount->account_setting_id = null;
					$lookingAccount->account_role_id = null;
					$lookingAccount->updated_at = null;

					$this->handleBlockedAccount($lookingAccount, $relasitonship1, $relasitonship2, $accountLookingResult);
					if ($lookingAccount) {
						$this->handleFriendAccount($lookingAccount, $relasitonship1, $relasitonship2, $accountLookingResult);
					}
					if ($lookingAccount) {
						$this->handleStrangerAccount($lookingAccount, $relasitonship1, $relasitonship2, $accountLookingResult);
					}

					$accountLookingResult->account = $lookingAccount;

					array_push($result, $accountLookingResult);
				}
			}
		}

		return $result;
	}
}
