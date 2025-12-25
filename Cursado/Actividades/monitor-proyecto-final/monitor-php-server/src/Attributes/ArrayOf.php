<?php

namespace App\Attributes;

use Attribute;
use App\Attributes\IAttribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class ArrayOf implements IAttribute
{
	public string $type;

	public function __construct(string $type)
	{
		$this->type = $type;
	}

	public function validate(mixed $value): ?string
	{
		if (!is_array($value)) {
			return "Value must be an array.";
		}

		foreach ($value as $item) {
			if (gettype($item) !== $this->type) {
				return "Array item must be of type {$this->type}.";
			}
		}
		return null;
	}
};
