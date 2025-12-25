<?php

namespace App\DTOs\Requests;

use App\Attributes\Numeric;


class CreateMetricRecordRequest
{
	#[Numeric]
	// #[Restricted([valores posibles o enum])] // Comment: Interesting implementation 
	public int $metricId;

	#[Numeric]
	public float $value;

	public function __construct(int $metricId, float $value)
	{
		$this->metricId = $metricId;
		$this->value = $value;
	}
};
