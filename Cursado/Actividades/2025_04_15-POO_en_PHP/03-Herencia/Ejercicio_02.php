<?php
	class Animal {
		public $species;

		public function __construct($species) {
			$this->species = $species;
		}

		public function makeSound() {
			echo "Animal makes a sound.\n";
		}
	}

	class Cat extends Animal {
		public function __construct($species) {
			parent::__construct($species);
		}

		public function makeSound() {
			$className = get_class($this);
			echo "{$className} says Miau!\n";
		}	
	}

	$cat = new Cat("Felis catus");
	$cat->makeSound();
?>
