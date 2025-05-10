<?php
	require_once "db.php";
	
	$pdo = Database::getPDOInstance(); 

	$movedAmount = 100;
	$idDeposit = 1;
	$idWithdrawl = 2;

	$sqlDeposit = "UPDATE accounts SET balance = balance + :deposit WHERE id = :id";
	$sqlWithdrawl = "UPDATE accounts SET balance = balance - :withdrawl WHERE id = :id";
	$sqlIsBalanceEnough = "SELECT balance FROM accounts WHERE id = :id";
	
	try {
		$stmt = $pdo->prepare($sqlIsBalanceEnough);
		$stmt->execute([
			':id' => $idWithdrawl
		]);
		$output = $stmt->fetch();
		
		if ($output['balance'] < $movedAmount) {
			echo "Amount in account with id {$idWithdrawl} doesn't have enough balance";
			exit(1);
		}
		
		$pdo->beginTransaction();
		
		$stmt = $pdo->prepare($sqlWithdrawl);
		$stmt->execute([
				':withdrawl' => $movedAmount,
				':id' => $idWithdrawl
		]);

		$stmt = $pdo->prepare($sqlDeposit);
		$stmt->execute([
				':deposit' => $movedAmount,
				':id' => 6
		]);
		
		$pdo->commit();
		
		echo "Successfully moved {$movedAmount} from account {$idWithdrawl} to account {$idDeposit}\n";
	} catch (PDOException $e) {
		$pdo->rollBack();
		error_log("Error en la operacion INSERT: {$e->getMessage()}");
	}
?>