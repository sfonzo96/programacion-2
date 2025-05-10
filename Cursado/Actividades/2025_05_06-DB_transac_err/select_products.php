<?php
require_once "db.php";
require_once "DAO/ProductDAO.php";

use Data\Database;
use DAO\ProductDAO;

$pdo = Database::getPDOInstance();
$productDAO = new ProductDAO($pdo);

// 2.
echo "Ejercicio 2:\n";
$products = $productDAO->getAllProducts();

foreach ($products as $product) {
	echo "Name: {$product['name']}\nPrice: \${$product['price']}\n\n";
}

// 3.
echo "Ejercicio 3:\n";
$productsName = "KINGSTON NV2 1TB SSD";
$product = $productDAO->getProductByName($productsName);
echo "Name: {$product->name}\nPrice: \${$product->price}\n\n";
