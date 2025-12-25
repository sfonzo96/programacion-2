<?php

namespace App\DTOs\Requests;

use App\Attributes\MaxLength;
use App\Attributes\MinLength;
use App\Attributes\Required;
use App\Interfaces\IRequest;

class CreateUserRequest
{
	#[Required]
	#[MinLength(5)]
	public string $firstName;

	#[Required]
	#[MinLength(5)]
	public string $lastName;

	#[Required]
	#[MinLength(5)]
	#[MaxLength(20)]
	public string $username;

	#[Required]
	#[MinLength(8)]
	#[MaxLength(20)]
	public string $password;

	#[Required]
	#[MinLength(8)]
	#[MaxLength(20)]
	public string $confirmPassword;

	public function __construct(string $firstName, string $lastName, string $username, string $password)
	{
		$this->firstName = $firstName;
		$this->lastName = $lastName;
		$this->username = $username;
		$this->password = $password;
	}
}
