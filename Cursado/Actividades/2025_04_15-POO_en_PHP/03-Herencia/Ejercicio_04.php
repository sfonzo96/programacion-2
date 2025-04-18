<?php
	abstract class Figure {
		abstract public  function calculateArea();
	}

	class Square extends Figure {
		public $side;

		public function __construct($side) {
			$this->side = $side;
		}

		public function calculateArea() {
			return $this->side ** 2;
		}
	}

	$square = new Square(15);
	echo "Square's area:\t{$square->calculateArea()}\n";
?>
