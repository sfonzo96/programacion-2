<?php

namespace DAO;

require_once "Models/Account.php";

use PDO;
use PDOException;
use Models\Account;


class AccountDAO
{
	private PDO $pdo;

	public function __construct(PDO $pdo)
	{
		$this->pdo = $pdo;
	}

	public function createAccount(Account $account): bool
	{
		$sql = "INSERT INTO account (balance) VALUES (:balance);";
		try {
			$stmt = $this->pdo->prepare($sql);
			$success = $stmt->execute([
				':balance' => $account->balance
			]);

			if (!$success) {
				return false;
			}

			return true;
		} catch (PDOException $e) {
			error_log("Error creating account: {$e->getMessage()}");
			return false;
		}
	}

	public function getAccountById(int $id): Account | null
	{
		$sql = "SELECT id, balance FROM accounts WHERE id = :id;";
		try {
			$stmt = $this->pdo->prepare($sql);
			$stmt->execute([
				':id' => $id
			]);

			$account = $stmt->fetch();

			if (!$account) {
				return null;
			}
			return new Account($account['balance'], $account['id']);
		} catch (PDOException $e) {
			error_log("Error getting account: {$e->getMessage()}");
			return null;
		}
	}

	// Quizás convenga refinar este método
	public function transferBalance(int $destinationId, int $originId, float $transferAmount): bool
	{
		$sqlWithdrawl = "UPDATE accounts SET balance = balance - :withdrawlAmount WHERE id = :originId;";
		$sqlDeposit = "UPDATE accounts SET balance = balance + :depositAmount WHERE id = :destinationId;";
		$sqlGetAccount = "SELECT id, balance FROM accounts WHERE id = :id;";

		try {
			$stmt = $this->pdo->prepare($sqlGetAccount);
			$stmt->execute([
				':id' => $originId
			]);
			$originAccount = $stmt->fetch(); // false if it doesnt exists

			if (!$originAccount || $originAccount['balance'] < $transferAmount) {
				// echo "Amount in account with id {$originId} doesn't have enough balance";
				return false;
			}

			$stmt->execute([
				':id' => $destinationId
			]);
			$destinationAccount = $stmt->fetch();

			if (!$destinationAccount) {
				return false;
			}

			$this->pdo->beginTransaction();

			$stmt = $this->pdo->prepare($sqlWithdrawl);
			$stmt->execute([
				':withdrawlAmount' => $transferAmount,
				':originId' => $originId
			]);

			$stmt = $this->pdo->prepare($sqlDeposit);
			$stmt->execute([
				':depositAmount' => $transferAmount,
				':destinationId' => $destinationId
			]);

			$this->pdo->commit();

			// echo "Successfully moved {$transferAmount} from account {$originId} to account {$destinationId}\n";
			return true;
		} catch (PDOException $e) {
			$this->pdo->rollBack();
			error_log("Error transfering money: {$e->getMessage()}");
			return false;
		}
	}

	public function deleteAccount(int $id): bool
	{
		// $sql = "UPDATE accounts SET status = false WHERE id = :id;" // Eliminación lógica
		$sql = "DELETE FROM accounts WHERE id = :id;";
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
			error_log("Error deleting account: {$e->getMessage()}");
			return false;
		}
	}
}
