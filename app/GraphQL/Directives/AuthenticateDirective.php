<?php

namespace App\GraphQL\Directives;

use App\Account;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Schema\Values\FieldValue;
use Nuwave\Lighthouse\Support\Contracts\FieldMiddleware;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\GraphQL\Entities\Result\ErrorResult;
use App\Helpers\JsonWebToken;

class AuthenticateDirective extends BaseDirective implements FieldMiddleware
{
	/**
	 * Wrap around the final field resolver.
	 *
	 * @param \Nuwave\Lighthouse\Schema\Values\FieldValue $fieldValue
	 * @param \Closure $next
	 * @return \Nuwave\Lighthouse\Schema\Values\FieldValue
	 */
	public function handleField(FieldValue $fieldValue, Closure $next): FieldValue
	{
		// Retrieve the existing resolver function
		/** @var Closure $previousResolver */
		$previousResolver = $fieldValue->getResolver();

		// Wrap around the resolver
		$wrappedResolver = function ($root, array $args, GraphQLContext $context, ResolveInfo $info) use ($previousResolver) {
			$root['verified_account'] = $this->authenticate();

			// Call the resolver, passing along the resolver arguments
			return $previousResolver($root, $args, $context, $info);
		};

		// Place the wrapped resolver back upon the FieldValue
		// It is not resolved right now - we just prepare it
		$fieldValue->setResolver($wrappedResolver);

		// Keep the middleware chain going
		return $next($fieldValue);
	}

	/**
	 * Read the token and export that account
	 *
	 * @return Account|null
	 */
	private function authenticate(): ?Account
	{
		$token = request()->header('token');
		if ($token) {
			$accountId = JsonWebToken::decode($token);
			return Account::find($accountId);
		} else {
			sleep(2);
			if (config('app.debug')) {
				ErrorResult::exit('missing the token');
			} else {
				exit;
			}
		}
	}
}
