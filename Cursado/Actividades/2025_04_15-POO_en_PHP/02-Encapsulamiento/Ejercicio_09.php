<?php
	class Circle {
		private $radius;

		public function __construct($radius) {
			$this->radius = $radius;
		}

		public function getRadius() {
			return $this->radius;
		}

		public function setRadius($radius) {
			if ($radius <= 0) {
				echo "Circle's radius must be positive.\n";
				return;
			}
			
			$this->radius = $radius;
		}
	}	

	$circle = new Circle(10);
	echo "Circle's radius:\t\t{$circle->getRadius()}\n";
	$circle->setRadius(-10);
	$circle->setRadius(15);
	echo "Circle's radius:\t\t{$circle->getRadius()}\n";
?>
