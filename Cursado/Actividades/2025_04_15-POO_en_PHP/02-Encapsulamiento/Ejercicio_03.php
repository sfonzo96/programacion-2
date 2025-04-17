<?php
	class Product {
		private $price = 0;
		
		public function __construct($price) {
			$this->price = $price;
		}

		public function getPrice() {
			return $this->price;
		}

		public function setPrice($price) {
			if (!($price > 0)) {
				echo "Price should be positive!\n";
				// exit(1);
				return;
			}

			$this->price = $price;
		}
	}

	$product = new Product(250);
	echo "Price:\t{$product->getPrice()}\n";
	$product->setPrice(300);
	$product->setPrice(-200); // Should print the line 14 message
	echo "Price:\t{$product->getPrice()}\n";
?>
