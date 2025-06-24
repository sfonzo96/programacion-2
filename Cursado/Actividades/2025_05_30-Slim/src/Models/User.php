<?php

namespace Models;

class User
{
	public ?int $id;
	public bool $status;
	public string $email;

	public function __construct(bool $status, string $email, ?int $id = null)
	{
		$this->status = $status;
		$this->email = $email;
		$this->id = $id;
	}
}
