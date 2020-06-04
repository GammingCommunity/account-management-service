<?php

namespace App\GraphQL\Queries;

use App\Account;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\AccountRelationship;
use App\Common\Helpers\AccountHelper;
use App\Enums\DbEnums\AccountPrivacyType;
use App\Enums\DbEnums\AccountRelationshipType;
use App\GraphQL\Entities\Result\FriendGettingResult;

class GetFriends
{
	/**
	 * Return a value for the field.
	 *
	 * @param  null  $rootValue Usually contains the result returned from the parent field. In this case, it is always `null`.
	 * @param  mixed[]  $args The arguments that were passed into the field.
	 * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext  $context Arbitrary data that is shared between all fields of a single query.
	 * @param  \GraphQL\Type\Definition\ResolveInfo  $resolveInfo Information about the query itself, such as the execution state, the field name, path to the field from the root, and more.
	 * @return array FriendGettingResult[]
	 */
	public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): array
	{
		$result = [];
		$currentAccount =  $rootValue['verified_account'];
		$friendName = $args['friend_name'] ?? null;

		if ($currentAccount) {
			$relationships = AccountRelationship::where('relationship_type', AccountRelationshipType::FRIEND)->where(function ($query) use ($currentAccount) {
				return $query->where('sender_account_id', $currentAccount->id)->orWhere('receiver_account_id', $currentAccount->id);
			})->get(['sender_account_id', 'receiver_account_id', 'updated_at']);

			$result = $this->getFriendsList($currentAccount->id, $relationships, $friendName);
		}

		return $result;
	}

	protected function getFriendsList(int $currentAccountId, $relationships, string $friendName = null): array
	{
		$result = [];

		foreach ($relationships as $relationship) {
			if ($currentAccountId === $relationship->sender_account_id) {
				$friend = $relationship->receiver;
			} else {
				$friend = $relationship->sender;
			}

			if ($friendName === null || strpos(strtoupper($friend->name), strtoupper($friendName)) !== false) {
				$friendResult = new FriendGettingResult($friend, $relationship->updated_at);
				$this->checkPrivacy($friendResult->friend);

				array_push($result, $friendResult);
			}
		}

		return $result;
	}

	protected function checkPrivacy(Account &$lookedAccount)
	{
		if ($lookedAccount->setting->birthmonth_privacy < AccountPrivacyType::PUBLIC) {
			$lookedAccount->birthmonth = null;
		}
		if ($lookedAccount->setting->birthyear_privacy < AccountPrivacyType::PUBLIC) {
			$lookedAccount->birthyear = null;
		}
		if ($lookedAccount->setting->email_privacy < AccountPrivacyType::PUBLIC) {
			$lookedAccount->email = null;
		}
		if ($lookedAccount->setting->phone_privacy < AccountPrivacyType::PUBLIC) {
			$lookedAccount->phone = null;
		}
	}
}
