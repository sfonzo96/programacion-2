<?php

namespace App\Attributes;

use Attribute;
use App\Attributes\IAttribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class MinValue implements IAttribute
{
	public int $minValue;

	public function __construct(int $minValue)
	{
		$this->minValue = $minValue;
	}

	public function validate(mixed $value): ?string
	{
		if ($value < $this->minValue) {
			return "Value must be at least {$this->minValue}.";
		}
		return null;
	}
};
