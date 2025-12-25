<?php

namespace App\Repositories;

use App\DTOs\Requests\CreateUserRequest;
use App\DTOs\Requests\UpdateUserPasswordRequest;
use App\DTOs\Requests\UpdateUserRoleRequest;
use App\Enums\UsersRoles;
use App\Interfaces\IRequest;
use PDO;
use PDOException;
use App\Models\User;
use App\Models\Role;
use DateTime;

class UsersRepository
{
	private PDO $pdo;

	public function __construct(PDO $pdo)
	{
		$this->pdo = $pdo;
	}

	public function create(CreateUserRequest $user): bool
	{
		$sql = "INSERT INTO users (enabled, first_name, last_name, username, password, role_id, created_at, last_login_at) VALUES (:enabled, :first_name, :last_name, :username, :password, :role_id, :created_at, :last_login_at);";

		try {
			$stmt = $this->pdo->prepare($sql);
			$success = $stmt->execute([
				":enabled" => 0,
				":first_name" => $user->firstName,
				":last_name" => $user->lastName,
				":username" => $user->username,
				":password" => password_hash($user->password, PASSWORD_BCRYPT),
				":role_id" => UsersRoles::WATCHER->value,
				":created_at" => (new DateTime())->format("Y-m-d H:i:s"),
				":last_login_at" => (new DateTime())->format("Y-m-d H:i:s")
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

	public function getAll(): array
	{
		$sql = "SELECT 
					u.id, 
					u.enabled,
					u.first_name, 
					u.last_name, 
					u.username, 
					u.password, 
					r.id AS role_id,
					r.name AS role_name,
					u.created_at, 
					u.last_login_at 
				FROM users AS u
				JOIN roles AS r ON u.role_id = r.id;";

		try {
			$stmt = $this->pdo->query($sql);
			$users = $stmt->fetchAll();

			if (count($users) == 0) {
				return [];
			}

			$users = array_map(function ($user) {
				return new User(
					$user["id"],
					$user["first_name"],
					$user["last_name"],
					$user["username"],
					$user["password"],
					$user["enabled"],
					new Role($user["role_id"], $user["role_name"]),
					new DateTime($user["created_at"]),
					new DateTime($user["last_login_at"])
				);
			}, $users);

			return $users;
		} catch (PDOException $e) {
			error_log("Error getting products: {$e->getMessage()}");
			return [];
		}
	}

	public function getByUsername(string $username): ?User
	{
		$sql = "SELECT 
					u.id, 
					u.enabled,
					u.first_name, 
					u.last_name, 
					u.username, 
					u.password, 
					r.id AS role_id,
					r.name AS role_name,
					u.created_at, 
					u.last_login_at 
				FROM users AS u
				JOIN roles AS r ON u.role_id = r.id
				WHERE u.username = :username;";

		try {
			$stmt = $this->pdo->prepare($sql);
			$stmt->execute([
				":username" => $username
			]);

			$user = $stmt->fetch();

			if (!$user) {
				return null;
			}

			return new User(
				$user["id"],
				$user["first_name"],
				$user["last_name"],
				$user["username"],
				$user["password"],
				(bool)$user["enabled"],
				new Role($user["role_id"], $user["role_name"]),
				new DateTime($user["created_at"]),
				new DateTime($user["last_login_at"])
			);
		} catch (PDOException $e) {
			error_log("Error finding user: {$e->getMessage()}");
			return null;
		}
	}

	public function getById(int $id): ?User
	{
		$sql = "SELECT 
					u.id, 
					u.enabled,
					u.first_name, 
					u.last_name, 
					u.username, 
					u.password, 
					r.id AS role_id,
					r.name AS role_name,
					u.created_at, 
					u.last_login_at 
				FROM users AS u
				JOIN roles AS r ON u.role_id = r.id
				WHERE u.id = :id;";

		try {
			$stmt = $this->pdo->prepare($sql);
			$stmt->execute([
				":id" => $id
			]);

			$user = $stmt->fetch();

			if (!$user) {
				return null;
			}

			return new User(
				$user["id"],
				$user["first_name"],
				$user["last_name"],
				$user["username"],
				$user["password"],
				(bool)$user["enabled"],
				new Role($user["role_id"], $user["role_name"]),
				new DateTime($user["created_at"]),
				new DateTime($user["last_login_at"])
			);
		} catch (PDOException $e) {
			error_log("Error finding user: {$e->getMessage()}");
			return null;
		}
	}

	public function updateUserPassword(UpdateUserPasswordRequest $user): bool
	{
		$sql = "UPDATE users
				SET password = :password
				WHERE id = :id;";

		try {
			$stmt = $this->pdo->prepare($sql);
			$success = $stmt->execute([
				":id" => $user->userId,
				":password" => password_hash($user->newPassword, PASSWORD_BCRYPT)
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
	public function updateUserRole(UpdateUserRoleRequest $user): bool
	{
		$sql = "UPDATE users
				SET role_id = :roleId
				WHERE id = :userId;";

		try {
			$stmt = $this->pdo->prepare($sql);
			$success = $stmt->execute([
				":userId" => $user->userId,
				":roleId" => $user->roleId,
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

	public function disable(int $userId): bool
	{
		$sql = "UPDATE users SET enabled = 0 WHERE id = :id;";
		try {
			$stmt = $this->pdo->prepare($sql);
			$success = $stmt->execute([
				":id" => $userId
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
