<?php

namespace App\Attributes;

use Attribute;
use App\Attributes\IAttribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Required implements IAttribute
{
	public function validate(mixed $value): ?string
	{
		if (empty($value)) {
			return "Value is required.";
		}
		return null;
	}
};
