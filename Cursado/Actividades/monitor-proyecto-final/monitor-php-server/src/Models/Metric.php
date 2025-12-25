<?php

namespace App\Models;

class Metric
{
	public int $id;
	public string $name;
	public string $description;
	public string $unit;
	public array $records;

	public function __construct(
		int $id,
		string $name,
		string $description,
		string $unit,
		array $records = []
	) {
		$this->id = $id;
		$this->name = $name;
		$this->description = $description;
		$this->unit = $unit;
		$this->records = $records;
	}
}
