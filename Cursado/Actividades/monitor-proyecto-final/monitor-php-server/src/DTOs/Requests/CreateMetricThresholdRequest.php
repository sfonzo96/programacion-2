<?php

namespace App\DTOs\Requests;

use App\Attributes\Alfanumeric;
use App\Attributes\MinLength;
use App\Attributes\Numeric;
use App\Attributes\Required;
use App\Interfaces\IRequest;

class CreateMetricThresholdRequest
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
	public int $metricId;

	#[Numeric]
	#[Required]
	public int $severityId;
};
