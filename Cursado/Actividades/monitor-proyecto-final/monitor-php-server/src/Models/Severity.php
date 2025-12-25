<?php

namespace App\Models;

use App\Enums\SeverityLevel;

class Severity
{
	public int $id;
	public string $name;
	public SeverityLevel $severity;

	public function __construct(
		int $id,
		string $name,
		SeverityLevel $severity,
	) {
		$this->id = $id;
		$this->name = $name;
		$this->severity = $severity;
	}
}
