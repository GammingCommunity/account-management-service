<?php

namespace App\Common;

use App\Common\AuthServiceResponseStatus;
use App\GraphQL\Entities\Result\ErrorResult;

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
		if($obj->status && $obj->data && $obj->describe){
			$this->status = $obj->status;
			$this->data = $obj->data;
			$this->describe = $obj->describe;
		} else {
			ErrorResult::exit('Failed to decode jsonString to AuthServiceResponse');
		}
	}
}
