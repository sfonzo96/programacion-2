<?php
	declare(strict_types=1);

	interface Swimmer {
		public function swim(): void;
	}

	class Person implements Swimmer {
		public function swim(): void {
			echo "Person is swimming in the pool.\n";
		}
	}

	class Fish implements Swimmer {
		public function swim(): void {
			echo "Fish is swimming in the sea.\n";
		}
	}

	function makeInstanceSwim(Swimmer $swimmer) {
		$swimmer->swim();
	}

	$person = new Person();
	$fish = new Fish();

	makeInstanceSwim($person);
	makeInstanceSwim($fish);
?>
