<?php
	declare(strict_types=1);

	class Employee {
		public string $name;
		protected float $salary; // Precio hora?

		public function __construct(string $name, float $salary) {
			$this->name = $name;
			$this->salary = $salary;
		}

		public function calculatePayment(): float {
			echo "Not implemented\n";
		}
	}

	class Freelancer extends Employee {
		private int $hours;
		
		public function __construct(string $name, float $salary, int $hours) {
			parent::__construct($name, $salary); 
			$this->hours = $hours;
		}
		
		public function getHours(): int {
			return $this->hours;
		}

		public function getSalary(): float {
			return $this->salary;
		}

		public function calculatePayment(): float {
			return $this->hours * $this->salary;
		}
	}	

	$freelancer = new Freelancer("Santi", 4, 20);
	echo "{$freelancer->name}'s payment is:\t$ {$freelancer->calculatePayment()}\nCalculated as hours * salary:\n\tHours:\t{$freelancer->getHours()}\n\tSalary:\t{$freelancer->getSalary()}\n";
?>
