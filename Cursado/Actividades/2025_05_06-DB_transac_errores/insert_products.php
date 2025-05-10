<?php
require_once 'db.php';
require_once 'DAO/ProductDao.php';
require_once 'Models/Product.php';

$pdo = Database::getPDOInstance();
$productDAO = new ProductDAO($pdo);

$products = [
	new Product('AMD Ryzen 9 5900X', 549),
	new Product('Kingston Fury Beast DDR4 32GB', 180),
	new Product('Western Digital Black SN850X 1TB SSD', 300)
];


foreach ($products as $product) {
	$success = $productDAO->createProduct($product);
	if (!$success) {
		echo "Creation of product {$product->name} went wrong.\n"; // Podría retornar un objeto con el estado (bool) y la causa
	} else {
		echo "Product created: {$product->name}.\n";
	}
};
