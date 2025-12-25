<?php

namespace App\Models;

class Network
{
	public int $id;
	public string $description;
	public string $ipAddress;
	public string $cidrMask;
	public bool $isOnline;

	public function __construct(int $id, string $description, string $ipAddress, string $cidrMask, bool $isOnline)
	{
		$this->id = $id;
		$this->description = $description;
		$this->ipAddress = $ipAddress;
		$this->cidrMask = $cidrMask;
		$this->isOnline = $isOnline;
	}
}
