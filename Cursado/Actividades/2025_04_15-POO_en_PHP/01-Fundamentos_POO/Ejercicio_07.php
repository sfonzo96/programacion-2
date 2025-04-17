<?php
	class Product {
		public $name;
		public $price;
		public $stock;
			
		public function __construct($name, $price, $stock) {
			$this->name = $name;
			$this->price = $price;
			$this->stock = $stock;
		}

		public function getStockValue() {
			return $this->price * $this->stock;
		}
	}

	$product = new Product("Producto 1", 2000, 25);
	echo "Stock value: {$product->getStockValue()}\n";
?>
