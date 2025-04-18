<?php
	declare(strict_types=1);

	class Animal {
		public string $name;

		public function __construct(string $name) {
			$this->name = $name;
		}

		public function move() {
			echo "The animal {$this->name} is moving.\n";
		}
	}

	class Fish extends Animal {
		public string $waterType;

		public function __construct(string $name, string $waterType) {
			parent::__construct($name);
			$this->waterType = $waterType;
		}

		public function move() {
			echo "Fish {$this->name} is swimming.\n";
		}
	}
	
	$fish = new Fish("Inti", "River");
	$fish->move();
?>
