<?php
	declare(strict_types=1);

	abstract class Vehicle {
		abstract public function move(): void;
	}

	class Bicycle extends Vehicle {
		public function move(): void {
			echo "Bicycle is cycling.\n";
		}
	}

	class Airplane extends Vehicle {
		public function move(): void {
			echo "Airplane is flying.\n";
		}
	}

	function makeVehicleMove(Vehicle $vehicle): void {
		$vehicle->move();
	}

	$bicycle = new Bicycle();
	$plane = new Airplane();
	
	makeVehicleMove($bicycle);
	makeVehicleMove($plane);
?>
