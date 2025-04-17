<?php
	class Worker {
		public $name;
		public $job;
		public $salary;

		public function __construct($name, $job, $salary) {
			$this->name = $name;
			$this->job = $job;
			$this->salary = $salary;
		}

		public function printInfo() {
			 echo "Worker info:\n\tName:\t{$this->name}\n\tJob:\t{$this->job}\n\tSalary:\t{$this->salary}\n";
		}
	}

	$worker = new Worker("Santiago", "Software Engineer", 2500);
	$worker->printInfo();
?>
