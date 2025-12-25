<?php

namespace App\DTOs\Requests;

use App\Attributes\Alfanumeric;
use App\Attributes\Numeric;
use App\Attributes\Required;
use App\Attributes\MinLength;
use App\Interfaces\IRequest;

class UpdateMetricThresholdRequest
{
	#[MinLength(5)]
	#[Alfanumeric]
	#[Required]
	public string $name;

	#[Numeric]
	#[Required]
	public float $value;

	#[Numeric]
	#[Required]
	public int $severityId;
};
