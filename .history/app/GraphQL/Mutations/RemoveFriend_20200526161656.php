<?php

namespace App\GraphQL\Mutations;

use App\Account;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\AccountRelationship;
use App\Enums\DbEnums\AccountPrivacyType;
use App\Enums\DbEnums\AccountRelationshipType;
use Illuminate\Database\Eloquent\Collection;

class RemoveFriend
{
	/**
	 * Return a value for the field.
	 *
	 * @param  null  $rootValue Usually contains the result returned from the parent field. In this case, it is always `null`.
	 * @param  mixed[]  $args The arguments that were passed into the field.
	 * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext  $context Arbitrary data that is shared between all fields of a single query.
	 * @param  \GraphQL\Type\Definition\ResolveInfo  $resolveInfo Information about the query itself, such as the execution state, the field name, path to the field from the root, and more.
	 * @return bool
	 */
	public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): bool
	{
		$result = false;
		$friendId = $args['friend_id'];
		$currentAccount = $rootValue['verified_account'];

		if ($currentAccount) {
			$currentAccountId = $currentAccount->id;
			$relationship = AccountRelationship::where('relationship_type', AccountRelationshipType::FRIEND)
				->where(function ($query) use ($currentAccountId, $friendId) {
					return $query->where('sender_account_id', $currentAccountId)->where('receiver_account_id', $friendId);
				})->first(['id']);

			if ($relationship) {
				$result = $relationship->delete();
			}
		}

		return $result;
	}
}
