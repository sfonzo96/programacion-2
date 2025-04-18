<?php
	declare(strict_types=1);

	interface ICalculable {
		public function calculatePerimeter(): float;
	}

	class Square implements ICalculable {
		public float $side;	

		public function __construct(float $side) {
			$this->side = $side;
		}

		public function calculatePerimeter(): float {
			return $this->side * 4;
		}
	}
	
	class Circle implements ICalculable {
		public float $radius;

		public function __construct(float $radius) {
			$this->radius = $radius;
		} 

		public function calculatePerimeter(): float {
			return 2 * pi() * $this->radius;
		}
	}

	$square = new Square(5);
	$circle = new Circle(2.5);
	$calculables = [$square, $circle];

	foreach ($calculables as $calculable) {
		$classname = get_class($calculable);
		echo "The {$classname} perimeter is:\t{$calculable->calculatePerimeter()}\n";
		if ($classname == "Square") {
			echo "It's side length is:\t{$calculable->side}";
		} else if ($classname == "Circle") {
			echo "It's radius length is:\t{$calculable->radius}";	
		}

		echo "\n";
	}
?>
