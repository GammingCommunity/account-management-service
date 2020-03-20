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
		$result = [];
		$receiverId = $args['receiver_id'];

		if ($rootValue['verified_account']) {
			$currentAccount = $rootValue['verified_account'];

			if ($currentAccount) {
				$friendRequests = AccountRelationship::where('relationship_type', AccountRelationshipType::FRIEND_REQUEST)->where('receiver_account_id', $currentAccount->id)->get(['sender_account_id', 'updated_at']);

				$result = $this->getFriendRequestsList($currentAccount->id, $friendRequests);
			}
		}

		return $result;
	}

	protected function getRelationships(int $senderId, int $receiverId): array{
		$result = [];

		$r1 = AccountRelationship::where('sender_account_id', $senderId)->first(['relationship_type']);
		$r2 = AccountRelationship::where('receiver_account_id', $senderId)->first(['relationship_type']);
	}
}
