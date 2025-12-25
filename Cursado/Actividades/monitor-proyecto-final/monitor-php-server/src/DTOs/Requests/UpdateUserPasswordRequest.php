<?php

namespace App\DTOs\Requests;

use App\Attributes\Alfanumeric;
use App\Attributes\MinLength;
use App\Attributes\Numeric;
use App\Attributes\Required;
use App\Interfaces\IRequest;


class UpdateUserPasswordRequest
{
	#[Numeric]
	#[Required]
	public int $userId;

	#[Alfanumeric]
	#[Required]
	public string $currentPassword;

	#[Alfanumeric]
	#[Required]
	#[MinLength(8)]
	public string $newPassword;

	#[Alfanumeric]
	#[Required]
	#[MinLength(8)]
	public string $confirmPassword;

	public function __construct(int $userId, string $currentPassword, string $newPassword)
	{
		$this->userId = $userId;
		$this->currentPassword = $currentPassword;
		$this->newPassword = $newPassword;
	}
};
