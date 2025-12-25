<?php

namespace App\DTOs\Requests;

use App\Attributes\MaxLength;
use App\Attributes\Numeric;
use App\Attributes\Required;

class CreateHostRequest
{
	#[Required]
	#[MaxLength(15)]
	public string $ipAddress;

	#[Required]
	#[MaxLength(17)]
	public string $macAddress;

	#[Required]
	#[Numeric]
	public int $networkId;

	#[MaxLength(255)]
	public ?string $hostname;

	public function __construct(string $ipAddress, string $macAddress, int $networkId, ?string $hostname)
	{
		$this->ipAddress = $ipAddress;
		$this->macAddress = $macAddress;
		$this->networkId = $networkId;
		$this->hostname = $hostname;
	}
}
