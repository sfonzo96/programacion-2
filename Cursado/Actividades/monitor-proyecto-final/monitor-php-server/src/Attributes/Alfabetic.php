<?php

namespace App\Attributes;

use Attribute;
use App\Attributes\IAttribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Alfabetic implements IAttribute
{

	public function validate(mixed $value): ?string
	{
		if (!ctype_alpha(str_replace(' ', '', $value))) {
			return "Not alfabetic";
		}

		return null;
	}
};
