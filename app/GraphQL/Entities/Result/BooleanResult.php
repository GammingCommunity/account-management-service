<?php

namespace App\GraphQL\Entities\Result;

class BooleanResult
{
	/**
	 * @var bool
	 */
	public $result;

	/**
	 * @var string
	 */
	public $describe;

	/**
	 * @param bool $result
	 * @param string $describe
	 */
	public function __construct(bool $result = false, string $describe = null)
	{
		$this->result = $result;
		$this->describe = $describe;
	}
}
