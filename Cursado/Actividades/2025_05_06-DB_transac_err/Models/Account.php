<?php

namespace Models;

class Account
{
	public ?int $id;
	public float $balance;

	public function __construct(float $balance, ?int $id = null)
	{
		$this->balance = $balance;
		$this->id = $id;
	}
}
