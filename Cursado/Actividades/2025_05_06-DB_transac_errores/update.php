<?php
	require_once "db.php";
	
	$pdo = Database::getPDOInstance(); 

	$newStatus = "active";
	$userId = "1";


	$sql = "UPDATE users SET status = :status WHERE id = :id";
	$stmt = $pdo->prepare($sql);

	try {
		$stmt->execute([
			':status' => $newStatus, 
			':id' => $userId
		]);
	} catch (PDOException $e) {
		exit("Error querying database");
	}
?>