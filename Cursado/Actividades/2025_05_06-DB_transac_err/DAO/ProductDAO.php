<?php

namespace DAO;

require_once "Models/Product.php";

use PDO;
use PDOException;
use Models\Product;

class ProductDAO
{
	private PDO $pdo;

	public function __construct(PDO $pdo)
	{
		$this->pdo = $pdo;
	}

	public function createProduct(Product $product): bool
	{
		$sql = "INSERT INTO products (name, price) VALUES (:name, :price);";
		try {
			$stmt = $this->pdo->prepare($sql);
			$success = $stmt->execute([
				':name' => $product->name,
				':price' => $product->price
			]);

			if (!$success) {
				return false;
			}

			return true;
		} catch (PDOException $e) {
			error_log("Error creating product: {$e->getMessage()}");
			return false;
		}
	}

	public function getProductById(int $id): Product | null
	{
		$sql = "SELECT id, name, price FROM products WHERE id = :id;";
		try {
			$stmt = $this->pdo->prepare($sql);
			$stmt->execute([
				':id' => $id
			]);

			$product = $stmt->fetch();

			if (!$product) {
				return null;
			}

			return new Product($product['name'], $product['price'], $product['id']);
		} catch (PDOException $e) {
			error_log("Error getting product: {$e->getMessage()}");
			return null;
		}
	}

	public function getProductByName(string $name): Product | null
	{
		$sql = "SELECT id, name, price FROM products WHERE name = :name;";
		try {
			$stmt = $this->pdo->prepare($sql);
			$stmt->execute([
				':name' => $name,
			]);

			$product = $stmt->fetch();

			if (!$product) {
				return null;
			}

			return new Product($product['name'], $product['price'], $product['id']);
		} catch (PDOException $e) {
			error_log("Error getting product: {$e->getMessage()}");
			return null;
		}
	}
	public function getAllProducts(): array | null
	{
		$sql = "SELECT id, name, price FROM products;";

		try {
			$stmt = $this->pdo->query($sql);
			$products = $stmt->fetchAll();

			if (count($products) == 0) {
				echo "Seems like there're no products...";
				return null;
			}

			return $products;
		} catch (PDOException $e) {
			error_log("Error getting products: {$e->getMessage()}");
			return null;
		}
	}

	public function updateProduct(Product $product): bool
	{
		$sql = "UPDATE products SET name = :name, price = :price WHERE id = :id;";
		try {
			$stmt = $this->pdo->prepare($sql);
			$success = $stmt->execute([
				':id' => $product->id,
				':name' => $product->name,
				':price' => $product->price
			]);

			if (!$success) {
				return false;
			}

			return true;
		} catch (PDOException $e) {
			error_log("Error updating product: {$e->getMessage()}");
			return false;
		}
	}

	public function deleteProduct(int $id): bool
	{
		// $sql = "UPDATE products SET status = false WHERE id = :id" // Eliminación lógica
		$sql = "DELETE FROM products WHERE id = :id;";
		try {
			$stmt = $this->pdo->prepare($sql);
			$success = $stmt->execute([
				':id' => $id
			]);

			if (!$success) {
				return false;
			}

			return true;
		} catch (PDOException $e) {
			error_log("Error deleting product: {$e->getMessage()}");
			return false;
		}
	}
}
