<?php

namespace App\Attributes;

use Attribute;
use App\Attributes\IAttribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class MaxLength implements IAttribute
{
	public int $length;

	public function __construct(int $length)
	{
		$this->length = $length;
	}

	public function validate(mixed $value): ?string
	{
		if (strlen((string)$value) > $this->length) {
			return "Max length exceeded";
		};

		return null;
	}
};
