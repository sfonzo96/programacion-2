<?php
	class Vehicle {
		private $kilometers = 0;
		
		public function __construct($kms) {
			$this->kilometers = $kms;
		}

		public function getKilometers() {
			return $this->kilometers;
		}

		public function move($kms) {
			if (!($kms > 0)) {
				echo "kms should be positive!\n";
				// exit(1);
				return;
			}

			$this->kilometers += $kms;
		}
	}

	$vehicle = new Vehicle(2000);
	echo "Kilometers:\t{$vehicle->getKilometers()}\n";
	$vehicle->move(3000);
	echo "Kilometers:\t{$vehicle->getKilometers()}\n";
?>
