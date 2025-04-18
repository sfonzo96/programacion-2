<?php
	declare(strict_types=1);
	
	class Product {
		public string $name;
		public float $price;

		public function __construct(string $name, float $price) {
			$this->name = $name;
			$this->price = $price;
		}

		public function showDetail(): void {
			echo "Product:\t{$this->name}.\nPrice:\t{$this->price}.\n";
		}
	}

	class ProductWithDiscount extends Product {
		public float $discount;

		public function __construct(string $name, float $price, float $discount) {
			parent::__construct($name, $price);
			$this->discount = $discount;
		}

		public function showDetail(): void {
			$priceWDiscount = $this->price * (1 - $this->discount / 100);
			echo "Product:\t{$this->name}.\nOriginal price:\t\t{$this->price}.\nWith {$this->discount} % discount:\t{$priceWDiscount}\n";

		}
	}
	
	$productWDiscount = new ProductWithDiscount("Producto 1", 1000, 10);
	$productWDiscount->showDetail();
?>
