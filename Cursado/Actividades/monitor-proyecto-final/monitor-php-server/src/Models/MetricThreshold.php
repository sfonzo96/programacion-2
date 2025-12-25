<?php

namespace App\Models;

class MetricThreshold
{
	public int $id;
	public string $name;
	public float $value;
	public Metric $metric;
	public User $creator;
	public Severity $severity;

	public function __construct(
		int $id,
		string $name,
		float $value,
		Metric $metric,
		User $creator,
		Severity $severity,
	) {
		$this->id = $id;
		$this->name = $name;
		$this->value = $value;
		$this->metric = $metric;
		$this->creator = $creator;
		$this->severity = $severity;
	}
}
