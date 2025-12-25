<?php

namespace App\Attributes;

use Attribute;
use App\Attributes\IAttribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Alfanumeric implements IAttribute
{
	public function validate(mixed $value): ?string
	{
		if (!ctype_alnum(str_replace(' ', '', $value))) {
			return "Not alfanumeric";
		}

		return null;
	}
};
