<?php
	class Circle {
		public $radius;
	
		public function __construct($radius) {
			$this->radius = $radius;
		}

		public function getPerimeter() {
			return 2 * pi() * $this->radius;
		}
	}

	$circle = new Circle(10);
	echo "Circle radius:\t{$circle->getPerimeter()}\n";
?>
