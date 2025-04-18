<?php
	declare(strict_types=1);

	abstract class Worker {
		abstract public function calculateIncome(): float;
	}

	class FixedWorker extends Worker {
		private float $salary;

		public function __construct(float $salary) {
			$this->salary = $salary;
		} 

		public function calculateIncome(): float {
			return $this->salary;
		} 
	}

	class TemporaryWorker extends Worker {
		private float $paymentPerDay;
		private int $jobDurationDays;

		public function __construct(float $paymentPerDay, int $jobDurationDay) {
			$this->paymentPerDay = $paymentPerDay;
			$this->jobDurationDay = $jobDurationDay;
		} 

		public function calculateIncome(): float {
			return $this->paymentPerDay * $this->jobDurationDay;
		}
	}

	$fixed = new FixedWorker(2500);
	$temporary = new TemporaryWorker(20, 15);
	$workers = [$fixed, $temporary];
	
	$i = 1;
	foreach ($workers as $worker) {
		echo "Worker {$i} earns:\t{$worker->calculateIncome()}";
		echo "\n";
	}
?>
