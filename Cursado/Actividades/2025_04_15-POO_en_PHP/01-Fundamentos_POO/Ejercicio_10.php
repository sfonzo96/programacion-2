<?php
	class Triangle {
		public $base;
		public $height;

		public function __construct($base, $height) {
			$this->base = $base;
			$this->height = $height;
		}

		public function getArea() {
			return $this->base * $this->height / 2;
		}
	}

	$triangle = new Triangle(10, 15);
	echo "Triangle area:\t{$triangle->getArea()}\n";
?>
