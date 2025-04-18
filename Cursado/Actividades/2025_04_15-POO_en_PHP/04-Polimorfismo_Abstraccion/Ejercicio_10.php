<?php
	declare(strict_types=1);

	abstract class Animal {
		abstract public function feed(): void;
	}

	class Lion extends Animal {
		public function feed(): void {
			echo "The lion ate a steak.\n";
		}
	}

	class Bird extends Animal {
		public function feed(): void {
			echo "The bird ate millet.\n";
		}
	}

	$lion =  new Lion();
	$bird = new Bird();
	$animals = [$lion, $bird];

	foreach ($animals as $animal) {
		$animal->feed();
	}
?>
