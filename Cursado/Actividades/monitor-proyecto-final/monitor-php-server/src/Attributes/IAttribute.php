<?php

namespace App\Attributes;

interface IAttribute
{
	public function validate(mixed $value): ?string;
}
