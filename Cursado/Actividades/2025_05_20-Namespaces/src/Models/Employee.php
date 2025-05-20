<?php

declare(strict_types=1);

namespace Models;

use Models\Base\Person;

class Employee extends Person
{
	private ?int $id;
	private float $salary = 0;

	public function __construct(string $firstName, string $lastName, float $salary, ?int $id = null)
	{
		parent::__construct($firstName, $lastName);
		$this->salary = $salary;
		$this->id = $id;
	}

	public function sayHello(): void
	{
		echo "Employee {$this->firstName} says hello.\n";
	}

	public function work(): void
	{
		echo "Employee {$this->firstName} is working.\n";
	}
}
