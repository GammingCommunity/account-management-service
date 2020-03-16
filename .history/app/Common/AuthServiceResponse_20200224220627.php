<?php

namespace App\Common;

use App\Common\AuthServiceResponseStatus;

class AuthServiceResponse{
	/**
	 * @var AuthServiceResponseStatus
	 */
	public $status;

	/**
	 * @var mixed
	 */
	public $data;

	/**
	 * @var string
	 */
	public $describe;

	/**
	 * @param string $jsonString
	 */
	public function __construct(string $jsonString)
	{
		$obj = json_decode($jsonString);
		$this->status = $obj->status;
		$this->data = $obj->data;
		$this->describe = $obj->describe;
	}
}
