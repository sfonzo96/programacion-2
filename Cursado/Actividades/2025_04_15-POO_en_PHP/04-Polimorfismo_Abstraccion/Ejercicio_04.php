<?php
	declare(strict_types=1);

	abstract class Figure {
		abstract public function calculateArea(): float;
	}

	class Triangle extends Figure { 
		private float $base;
		private float $height;
	
		public function __construct(float $base, float $height) {
			$this->base = $base;
			$this->height = $height;
		}
		
		public function calculateArea(): float {
			return ($this->base * $this->height) / 2;
		}
	}

	class Circle extends Figure {
		private float $radius;

		public function __construct(float $radius) {
			$this->radius = $radius;
		}

		public function calculateArea(): float {
			return  pi() * $this->radius ** 2; 
		}
	}

	$triangle = new Triangle(10, 5.5);
	$circle = new Circle(10);

	$figures = [];
	array_push($figures, $triangle);
	array_push($figures, $circle);

	foreach ($figures as $figure) {
		$className = get_class($figure);
		echo "Area of {$className}:\t{$figure->calculateArea()}.\n";		
	}
?>
