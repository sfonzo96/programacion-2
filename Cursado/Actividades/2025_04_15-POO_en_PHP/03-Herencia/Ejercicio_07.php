<?php
	declare(strict_types=1);
	
	class Instrument {
		public function play() {
			echo "Instrument is making a sound.\n";
		}
	}	

	class Piano extends Instrument {
		public function play() {
			echo "Piano is making a sound.\n";
		}
	}

	$piano = new Piano();
	$piano->play();
?>
