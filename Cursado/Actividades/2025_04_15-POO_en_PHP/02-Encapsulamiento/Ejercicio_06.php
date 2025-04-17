<?php 
	class Rectangle {
		private $width;
		private $height;

		public function __construct($width = 0, $height = 0) {
			$this->width = $width;
			$this->height = $height;
		}
	
		public function getWidth() {
			return $this->width;
		}
	
		public function getHeight() {
			return $this->height;
		}
		
		public function setDimensions($width, $height) {
			if (!($width > 0 && $height > 0)) {
				echo "Both width and height should be positive values!";
				return;
			}

			$this->width = $width;
			$this->height = $height;
		}

	}
	
	$rectangle = new Rectangle(10, 10);
	echo "El ancho es de:\t\t{$rectangle->getWidth()}\nLa altura es de:\t{$rectangle->getHeight()}\n";
	echo "\n";
	$rectangle->setDimensions(20, 50);
	echo "El ancho es de:\t\t{$rectangle->getWidth()}\nLa altura es de:\t{$rectangle->getHeight()}\n";
?>
