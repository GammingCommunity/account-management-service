<?php

namespace App\GraphQL\Queries;

use App\Account;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\AccountRelationship;
use App\Enums\DbEnums\AccountPrivacyType;
use App\Enums\DbEnums\AccountRelationshipType;
use App\GraphQL\Entities\Result\FriendRequestingResult;

class GetFriendRequests
{
	/**
	 * Return a value for the field.
	 *
	 * @param  null  $rootValue Usually contains the result returned from the parent field. In this case, it is always `null`.
	 * @param  mixed[]  $args The arguments that were passed into the field.
	 * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext  $context Arbitrary data that is shared between all fields of a single query.
	 * @param  \GraphQL\Type\Definition\ResolveInfo  $resolveInfo Information about the query itself, such as the execution state, the field name, path to the field from the root, and more.
	 * @return array FriendRequestingResult[]
	 */
	public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): array
	{
		$result = [];
		$currentAccount = $rootValue['verified_account'];
		
		if ($currentAccount) {
			$friendRequests = AccountRelationship::where('relationship_type', AccountRelationshipType::FRIEND_REQUEST)->where('receiver_account_id', $currentAccount->id)->get(['sender_account_id', 'updated_at']);

			$result = $this->getFriendRequestsList($currentAccount->id, $friendRequests);
		}

		return $result;
	}

	protected function getFriendRequestsList(int $id, $relationships): array
	{
		$result = [];

		foreach ($relationships as $relationship) {
			$friendResult = new FriendRequestingResult($relationship->sender, $relationship->updated_at);

			$this->checkPrivacy($friendResult->account);

			array_push($result, $friendResult);
		}

		return $result;
	}

	protected function checkPrivacy(Account &$lookedAccount)
	{
		if ($lookedAccount->setting->birthmonth_privacy !== AccountPrivacyType::PUBLIC) {
			$lookedAccount->birthmonth = null;
		}
		if ($lookedAccount->setting->birthyear_privacy !== AccountPrivacyType::PUBLIC) {
			$lookedAccount->birthyear = null;
		}
		if ($lookedAccount->setting->email_privacy !== AccountPrivacyType::PUBLIC) {
			$lookedAccount->email = null;
		}
		if ($lookedAccount->setting->phone_privacy !== AccountPrivacyType::PUBLIC) {
			$lookedAccount->phone = null;
		}
	}
}
