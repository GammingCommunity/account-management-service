<?php

namespace App\GraphQL\Queries;

use App\Account;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\AccountRelationship;
use App\Enums\DbEnums\AccountPrivacyType;
use App\Enums\DbEnums\AccountRelationshipType;
use App\GraphQL\Entities\Result\FriendRequestingResult;

class SendFriendRequest
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
		$receiverId = $args['receiver_id'];
		$currentAccount = $rootValue['verified_account'];

		if ($currentAccount) {
			$relasitonship = AccountRelationship::where(function ($query) use ($receiverId, $currentAccount) {
				return $query->where('sender_account_id', $currentAccount->id)->where('receiver_account_id', $receiverId);
			})->orWhere(function ($query) use ($receiverId, $currentAccount) {
				return $query->where('sender_account_id', $receiverId)->where('receiver_account_id', $currentAccount->id);
			})->first(['relationship_type']);
			
		}

		return $result;
	}

}
