<?php

namespace App\DTOs\Requests;

use App\Attributes\Required;
use App\Attributes\MaxLength;
use App\Attributes\MaxValue;
use App\Attributes\MinValue;

class CreateNetworkRequest
{
	#[Required]
	#[MaxLength(15)]
	// #[MatchRegex()] // Comment: Interesting for IP address validation
	public string $ipAddress;

	#[Required]
	#[MinValue(1)]
	#[MaxValue(32)]
	public int $CIDRMask;

	#[MaxLength(50)]
	public string $description = "";

	public function __construct(string $ipAddress, int $CIDRMask)
	{
		$this->ipAddress = $ipAddress;
		$this->CIDRMask = $CIDRMask;
	}
}
