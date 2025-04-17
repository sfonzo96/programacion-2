<?php
	class Person {
		public $name;
		public $age;

		public function __construc($name, $age) {
			$this->name = $name;
			$this->age = $age;
		}

		public function isAdult() {
			return $this->age >= 18;
		}
	}

	$younger = new Person("Santiaguito", 17);
    echo "isAdult:" . ($younger->isAdult() ? 'True' : 'False');
	echo "\n";
?>
