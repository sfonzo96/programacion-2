<?php
	class Vehicle {
		public $brand;
		
		public function __construct($brand) {
			$this->brand = $brand;
		}

		public function move() {
			echo "Vehicle {$this->brand} is moving.\n";
		}
	}

	class Motorcycle extends Vehicle {
		public $engineCapacity;

		public function __construct($brand, $engineCapacity) {
			parent::__construct($brand);
			$this->engineCapacity = $engineCapacity;
		}

		public function move() {
			echo "Motorcicle {$this->brand} ({$this->engineCapacity} cc.) is moving.\n";
		}
	}

	$motorcycle = new Motorcycle("Husqvarna", 250);
	$motorcycle->move();
?>
