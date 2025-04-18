<?php
	declare(strict_types=1);

	abstract class Instrument {
		abstract public function play(): void;	
	}

	class Violin extends Instrument {
		public function play(): void {
			echo "Violin is playing.\n";
		}
	}

	class Drums extends Instrument {
		public function play(): void {
			echo "Drums are playing.\n";
		}
	}

	$violin = new Violin();
	$drums = new Drums();
	$instruments = [$violin, $drums];

	foreach ($instruments as $instrument) {
		$instrument->play();
	}
?>
