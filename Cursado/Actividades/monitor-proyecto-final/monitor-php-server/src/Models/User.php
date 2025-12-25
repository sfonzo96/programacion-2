<?php

namespace App\Models;

use DateTime;

class User
{
	public int $id;
	public bool $isEnabled;
	public string $firstName;
	public string $lastName;
	public string $username;
	public string $password;
	public Role $role;
	public DateTime $createdAt;
	public DateTime $lastLoginAt;

	public function __construct(
		int $id,
		string $firstName,
		string $lastName,
		string $username,
		string $password,
		bool $isEnabled,
		Role $role,
		DateTime $createdAt,
		DateTime $lastLoginAt,
	) {
		$this->id = $id;
		$this->isEnabled = false;
		$this->firstName = $firstName;
		$this->lastName = $lastName;
		$this->username = $username;
		$this->password = $password;
		$this->isEnabled = $isEnabled;
		$this->role = $role;
		$this->createdAt = $createdAt;
		$this->lastLoginAt = $lastLoginAt;
	}
}
