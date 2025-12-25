<?php

namespace App\Attributes;

use Attribute;
use App\Attributes\IAttribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class MaxValue implements IAttribute
{
	public int $maxValue;

	public function __construct(int $maxValue)
	{
		$this->maxValue = $maxValue;
	}

	public function validate(mixed $value): ?string
	{
		if ($value > $this->maxValue) {
			return "Value must be at most {$this->maxValue}.";
		}
		return null;
	}
};
