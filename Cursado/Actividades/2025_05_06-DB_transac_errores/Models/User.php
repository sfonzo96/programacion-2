<?php
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
}
