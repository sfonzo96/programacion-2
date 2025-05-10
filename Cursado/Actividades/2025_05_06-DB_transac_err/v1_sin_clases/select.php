<?php
	require_once 'db.php';
	$pdo = Database::getPDOInstance(); 

	// 2.
	$sql = "SELECT id, name, price FROM products ORDER BY id DESC";
	$stmt = $pdo->query($sql);

	$products = $stmt->fetchAll();
	
	foreach ($products as $product) {
		echo "Name: {$product['name']}\nPrice: \${$product['price']}\n\n";
	}

	// 3.
	echo "Ejercicio 3:\n";
	$productsName = "KINGSTON NV2 1TB SSD";
	$sql = "SELECT id, name, price FROM products WHERE name = :name";
	$stmt = $pdo->prepare($sql);
	try {
		$stmt->execute([':name' => $productsName]);
		$product = $stmt->fetch();
	} catch (PDOException $e) {
		exit("Error querying database");
	}

	echo "Name: {$product['name']}\nPrice: \${$product['price']}\n\n";
?>