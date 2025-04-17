<?php
	class Car {
		public $brand;
		public $model;
		public $colour;

		public function __construct($brand, $model, $colour) {
			$this->brand = $brand;
			$this->model = $model;
			$this->colour = $colour;
		}

		public function printDetails() {
			echo "Car details:\n\tBrand: {$this->brand}\n\tModel: {$this->model}\n\tColour: {$this->colour}\n";
		}
	}

	$sample_car = new Car("Toyota","Corolla 2015","White");
	$sample_car->printDetails();
?>
