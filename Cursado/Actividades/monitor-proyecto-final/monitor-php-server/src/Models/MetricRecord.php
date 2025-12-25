<?php

namespace App\Models;

use DateTime;

class MetricRecord
{
	public int $metricId;
	public DateTime $createdAt;
	public float $value;

	public function __construct(
		int $metricId,
		float $value,
		DateTime $createdAt,
	) {
		$this->metricId = $metricId;
		$this->value = $value;
		$this->createdAt = $createdAt;
	}
}
