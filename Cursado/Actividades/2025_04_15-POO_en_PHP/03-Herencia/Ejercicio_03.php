<?php
	class Person {
		public $name;
		public $age;

		public function __construct($name, $age) {
			$this->name = $name;
			$this->age = $age;
		}
		
		public function greet() {
			echo "Person {$this->name} says hi.\n";
		}
	}

	class Profesor extends Person {
		public $subject;

		public function __construct($name, $age, $subject) {
			parent::__construct($name, $age);
			$this->subject = $subject;
		}

		public function greet() {
			echo "Profesor {$this->name} of subject {$this->subject} says hi.\n";
		}
	}

	$profesor = new Profesor("Sebastian Bruselario", "30", "Programacion 2");
	$profesor->greet();
?>
