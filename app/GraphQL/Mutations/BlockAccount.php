<?php

namespace App\GraphQL\Mutations;

use App\Account;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\AccountRelationship;
use App\Enums\DbEnums\AccountPrivacyType;
use App\Enums\DbEnums\AccountRelationshipType;
use App\GraphQL\Entities\Result\ResultCRUD;
use Illuminate\Database\Eloquent\Collection;

class BlockAccount
{
	/**
	 * Return a value for the field.
	 *
	 * @param  null  $rootValue Usually contains the result returned from the parent field. In this case, it is always `null`.
	 * @param  mixed[]  $args The arguments that were passed into the field.
	 * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext  $context Arbitrary data that is shared between all fields of a single query.
	 * @param  \GraphQL\Type\Definition\ResolveInfo  $resolveInfo Information about the query itself, such as the execution state, the field name, path to the field from the root, and more.
	 * @return ResultCRUD
	 */
	public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): ResultCRUD
	{
		$result = new ResultCRUD();
		$accountId = $args['account_id'];
		$currentAccount = $rootValue['verified_account'];

		if ($currentAccount) {
			$currentAccountId = $currentAccount->id;

			$relationships = AccountRelationship::whereRaw("(`sender_account_id` = {$currentAccountId} and `receiver_account_id` = {$accountId}) or (`sender_account_id` = {$accountId} and `receiver_account_id` = {$currentAccountId})")->get(['id']);

			if ($relationships) {
				$result->success = true;
				$result->message = '';

				foreach ($relationships as $relationship) {
					$result->success &= $relationship->delete();
					if (!$result->success) {
						$result->message += $relationship->id . ' ';
					}
				}

				if ($result->success) {
					UnfollowAccount::unfollow($accountId, $currentAccount);
				}
			}
		}

		return $result;
	}
}
