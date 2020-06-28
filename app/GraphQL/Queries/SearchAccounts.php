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
use App\GraphQL\Entities\Result\AccountLookingResult;
use Illuminate\Database\Eloquent\Collection;

class SearchAccounts
{
	/**
	 * Return a value for the field.
	 *
	 * @param  null  $rootValue Usually contains the result returned from the parent field. In this case, it is always `null`.
	 * @param  mixed[]  $args The arguments that were passed into the field.
	 * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext  $context Arbitrary data that is shared between all fields of a single query.
	 * @param  \GraphQL\Type\Definition\ResolveInfo  $resolveInfo Information about the query itself, such as the execution state, the field name, path to the field from the root, and more.
	 * @return array AccountLookingResult[]
	 */
	public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): array
	{

		$result = [];
		$searchKey = $args['key'];
		$excludeIds = $args['exclude_ids'] ?? [];
		$currentAccount = $rootValue['verified_account'];

		if ($currentAccount) {
			$result = LookAccount::look($currentAccount, $this->findAccounts($currentAccount->id, $searchKey, $excludeIds));
		}

		return $result;
	}

	protected function findAccounts(int $currentAccountId, string $key, array $excludeIds): array
	{
		$accounts = [];

		foreach ($this->findAccountsByString($currentAccountId, $key, $excludeIds) as $account) {
			array_push($accounts, $account);
		}

		return $accounts;
	}

	protected function idArrayToString(array $ids): string{
		$result = '';

		foreach ($ids as $id){
			$result .= " `id` != {$id} and";
		}

		return $result;
	}

	protected function findAccountsByString(int $currentAccountId, $key, array $excludeIds): Collection
	{
		return Account::whereRaw("{$this->idArrayToString($excludeIds)} `id` != {$currentAccountId} and (CONVERT(`id`, CHAR) = '{$key}' or UPPER(`describe`) like UPPER('%{$key}%') or UPPER(`name`) like UPPER('%{$key}%'))")->get();
	}
}
