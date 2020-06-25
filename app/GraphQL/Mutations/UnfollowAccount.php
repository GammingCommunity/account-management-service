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

class UnfollowAccount
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
		
		return self::unfollow($receiverId, $currentAccount);
	}

	public static function unfollow(int $ownerId, Account $follower): ResultCRUD{
		$result = new ResultCRUD();
		$follows = Follow::where('owner_id', '=', $ownerId)->where('follower_id', '=', $follower->id)->get();

		if ($follows) {
			$result->success = true;
			$result->message = '';
			
			foreach ($follows as $follow){
				$result->success &= $follow->delete();
				if(!$result->success){
					$result->message += $follow->id . ' ';
				}
			}
		} else {
			$result->cussess = true;
			$result->message = 'You have never followed this person.';
			$follow = new Follow();
			$follow->owner_id = $ownerId;
			$follow->follower_id = $follower->id;
			
			$result->success = $follow->save();
		}

		return $result;
	}
}
