<?php
	declare(strict_types=1);

	class Vehicle {
		protected float $speed;

		public function __construct(float $speed) {
			$this->speed = $speed;			
		}

		public function xlr8(): void {
			echo "Vehicle accelerates";
		}

		public function getSpeed(): float {
			return $this->speed;
		}
	}

	class Truck extends Vehicle {
		public function __construct(float $speed) {
			parent::__construct($speed);
		}
		
		public function xlr8(): void {
			$this->speed += 10;
		}
	}

	$truck = new Truck(90);
	echo "Truck speed is:\t{$truck->getSpeed()} km\h.\n";
	$truck->xlr8();
	echo "Truck speed is:\t{$truck->getSpeed()} km\h.\n";
?>
