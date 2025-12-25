<?php

namespace App\DTOs\Requests;

use App\Models\Host;
use App\Attributes\ArrayOf;
use App\Attributes\Numeric;
use App\Attributes\Required;

class UploadHostsReportRequest
{
	#[Required]
	#[Numeric]
	public int $networkId;

	#[Required]
	#[ArrayOf(Host::class)]
	public array $hosts;
};
