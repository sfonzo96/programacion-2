<?php

namespace App\Attributes;

use Attribute;
use App\Attributes\IAttribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Numeric implements IAttribute
{
	public function validate(mixed $value): ?string
	{
		if (!is_numeric($value)) {
			return "Not numeric";
		}

		return null;
	}
};
