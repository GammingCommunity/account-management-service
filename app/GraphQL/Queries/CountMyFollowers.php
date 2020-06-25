<?php

namespace App\GraphQL\Queries;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\AccountRelationship;
use App\Enums\DbEnums\AccountRelationshipType;
use App\Enums\DbEnums\AccountPrivacyType;
use App\Account;
use App\AccountSetting;
use App\Common\Helpers\AccountHelper;
use App\Follow;
use App\GraphQL\Entities\Result\AccountLookingResult;

class CountMyFollowers
{
	/**
	 * Return a value for the field.
	 *
	 * @param  null  $rootValue Usually contains the result returned from the parent field. In this case, it is always `null`.
	 * @param  mixed[]  $args The arguments that were passed into the field.
	 * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext  $context Arbitrary data that is shared between all fields of a single query.
	 * @param  \GraphQL\Type\Definition\ResolveInfo  $resolveInfo Information about the query itself, such as the execution state, the field name, path to the field from the root, and more.
	 * @return int
	 */
	public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): int
	{
		$currentAccount = $rootValue['verified_account'];

		return self::count($currentAccount->id);
	}

	public static function count(int $accountId): int
	{
		return Follow::where('owner_id', '=', $accountId)->select(['owner_id', 'follower_id'])->groupBy('follower_id')->get()->count();
	}
}
