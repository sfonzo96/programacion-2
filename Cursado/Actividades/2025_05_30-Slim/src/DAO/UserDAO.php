<?php

namespace DAO;

use Error;
use PDO;
use PDOException;
use Models\User;

class UserDAO
{
	private PDO $pdo;

	public function __construct(PDO $pdo)
	{
		$this->pdo = $pdo;
	}

	public function createUser(User $user): bool
	{
		$sql = "INSERT INTO users (email, status) VALUES (:email, :status);";
		try {
			$stmt = $this->pdo->prepare($sql);
			$success = $stmt->execute([
				':email' => $user->email,
				':status' => $user->status ? 0 : 1,
			]);

			if (!$success) {
				return false;
			}

			return true;
		} catch (PDOException $e) {
			error_log("Error creating user: {$e->getMessage()}");
			return false;
		}
	}

	public function getAllUsers(): array
	{
		$sql = "SELECT id, status, email FROM users;";

		try {
			$stmt = $this->pdo->query($sql);
			$users = $stmt->fetchAll();

			if (count($users) == 0) {
				return [];
			}

			return $users;
		} catch (PDOException $e) {
			error_log("Error getting products: {$e->getMessage()}");
			return [];
		}
	}

	public function getUserById(int $id): User | null
	{
		$sql = "SELECT id, status, email FROM users WHERE id = :id;";
		try {
			$stmt = $this->pdo->prepare($sql);
			$stmt->execute([
				':id' => $id
			]);

			$user = $stmt->fetch();

			if (!$user) {
				return null;
			}

			return new User($user['status'], $user['email'], $user['id']);
		} catch (PDOException $e) {
			error_log("Error finding user: {$e->getMessage()}");
			return null;
		}
	}

	public function updateUser(User $user, $id): bool
	{
		$sql = "UPDATE users SET status = :status, email = :email WHERE id = :id;";
		try {
			$stmt = $this->pdo->prepare($sql);
			$success = $stmt->execute([
				':id' => $id,
				':email' => $user->email,
				':status' => $user->status ? 1  : 0,
			]);

			if (!$success) {
				return false;
			}

			return true;
		} catch (PDOException $e) {
			error_log("Error updating user: {$e->getMessage()}");
			return false;
		}
	}

	public function updateUserStatus(string $status, int $id): bool
	{
		$sql = "UPDATE users SET status = :status WHERE id = :id;";

		try {
			$stmt = $this->pdo->prepare($sql);
			$success = $stmt->execute([
				':id' => $id,
				':status' => $status ? 1 : 0,
			]);

			if (!$success) {
				return false;
			}

			return true;
		} catch (PDOException $e) {
			error_log("Error updating user: {$e->getMessage()}");
			return false;
		}
	}

	public function deleteUser(int $id): bool
	{

		$user = $this->getUserById($id);
		if (!$user) {
			return false;
		}
		$sql = "DELETE FROM users WHERE id = :id;";
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
			error_log("Error deleting user: {$e->getMessage()}");
			return false;
		}
	}
}
