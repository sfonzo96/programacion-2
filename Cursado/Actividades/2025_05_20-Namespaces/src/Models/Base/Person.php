<?php

declare(strict_types=1);

namespace Models\Base;

abstract class Person
{
	public string $firstName;
	public string $lastName;

	public function __construct(string $firstName, string $lastName)
	{
		$this->firstName = $firstName;
		$this->lastName = $lastName;
	}

	public abstract function sayHello(): void;
}
