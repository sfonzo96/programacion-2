<?php

namespace App\Attributes;

use Attribute;
use App\Attributes\IAttribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Email implements IAttribute
{
	public function validate(mixed $value): ?string
	{
		if (filter_var($value, FILTER_VALIDATE_EMAIL) === false) {
			return "Invalid email format.";
		}
		return null;
	}
};
