<?php

declare(strict_types=1);

namespace Models;

class User
{
	public ?int $id;
	public string $status;
	public string $email;

	public function __construct(string $status, string $email, ?int $id = null)
	{
		$this->status = $status;
		$this->email = $email;
		$this->id = $id;
	}

	public function sayHello(): void
	{
		echo "User {$this->email} says Hello.\n";
	}

	public function getName()
	{
		return explode("@", $this->email)[0];
	}
}
