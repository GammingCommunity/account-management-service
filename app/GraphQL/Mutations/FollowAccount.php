<?php

namespace App\GraphQL\Mutations;

use App\Account;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\AccountRelationship;
use App\Enums\DbEnums\AccountPrivacyType;
use App\Enums\DbEnums\AccountRelationshipType;
use App\Follow;
use App\GraphQL\Entities\Result\ResultCRUD;
use Illuminate\Database\Eloquent\Collection;
use PDO;

class FollowAccount
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
		$receiverId = $args['account_id'];
		$currentAccount = $rootValue['verified_account'];

		return self::follow($receiverId, $currentAccount);
	}

	public static function follow(int $ownerId, int $followerId): ResultCRUD
	{
		$result = new ResultCRUD();

		if ($ownerId === $followerId) {
			$result->message = 'You are fucking wrong man!!!';
		} else {
			if (Account::find($ownerId)) {
				if (Follow::where('owner_id', '=', $ownerId)->where('follower_id', '=', $followerId)->exists()) {
					$result->success = true;
					$result->message = 'You are following this person!';
				} else {
					$follow = new Follow();
					$follow->owner_id = $ownerId;
					$follow->follower_id = $followerId;

					$result->success = $follow->save();
				}
			} else {
				$result->message = 'You are fucking wrong man!!!';
			}
		}

		return $result;
	}
}
