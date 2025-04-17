<?php
	class Employee {
		private $salary;

		public function __construct($salary) {
			$this->salary = $salary;
		}

		public function getSalary() {
			return $this->salary;
		}

		public function increaseSalary($percentualIncrease) {
			if (!is_numeric($percentualIncrease)) {
				echo "Increment should be numeric!";
				return;
			}

			$this->salary *= 1 + $percentualIncrease / 100;
		}
	}

	$employee = new Employee(2500);
	echo "Employee's salary:\t\t{$employee->getSalary()}.\n";
	$employee->increaseSalary(10);
	echo "Employee's salary:\t\t{$employee->getSalary()}.\n";
?>

