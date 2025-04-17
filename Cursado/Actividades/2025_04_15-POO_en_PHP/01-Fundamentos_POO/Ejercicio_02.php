<?php
	class Rectangle {
		public $height;
		public $width;

		public function getArea() {
			$area = $this->height * $this->width;
			return $area . "\n";
		}	
	}

	$rectangle = new Rectangle();
	$rectangle->height = 10;
	$rectangle->width = 20;

	print($rectangle->getArea());
?>
