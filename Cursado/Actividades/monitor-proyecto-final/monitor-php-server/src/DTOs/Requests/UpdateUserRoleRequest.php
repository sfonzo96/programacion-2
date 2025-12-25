<?php

namespace App\DTOs\Requests;

use App\Attributes\Numeric;
use App\Attributes\Required;
use App\Interfaces\IRequest;


class UpdateUserRoleRequest
{
	#[Numeric]
	#[Required]
	public int $userId;

	#[Numeric]
	#[Required]
	public int $roleId;

	public function __construct(int $userId, int $roleId)
	{
		$this->userId = $userId;
		$this->roleId = $roleId;
	}
};
