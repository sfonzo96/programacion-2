<?php
	require_once 'db.php';
	
	$pdo = Database::getPDOInstance(); 

	$sql = "INSERT INTO products (name, price) VALUES (:name, :price)";
	$stmt = $pdo->prepare($sql);
	$products = [
		[
			'name' => 'Intel i5 Ultra 225', 
			'price' => 350000.00],
		[
  		    'name' => 'Procesador Ryzen 5 5600X',
  		    'price' => 180
  		],
  		[
  		    'name' => 'GeForce RTX 3060',
  		    'price' => 320
  		],
  		[
  		    'name' => 'Memoria DDR4 16GB',
  		    'price' => 75
  		]
		];

		try {
			$pdo->beginTransaction();
			foreach ($products as $product) {
				$stmt->execute([
					':name' => $product["name"],
					':price' => $product["price"]
				]);
			};
			$pdo->commit();
		} catch (PDOException $e) {
			$pdo->rollBack();
			error_log("Error en la operacion INSERT: {$e->getMessage()}");
		}
?>