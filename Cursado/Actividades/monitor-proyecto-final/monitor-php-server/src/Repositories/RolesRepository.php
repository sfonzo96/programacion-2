<?php

namespace App\Repositories;

use App\Models\Role;
use PDO;
use PDOException;

class RolesRepository
{
	private PDO $pdo;

	public function __construct(PDO $pdo)
	{
		$this->pdo = $pdo;
	}

	public function getAll()
	{
		$sql = "SELECT id, name from roles AS r;";

		try {
			$stmt = $this->pdo->query($sql);
			$roles = $stmt->fetchAll();

			if (count($roles) == 0) {
				return [];
			}

			// TODO: Use a mapper to convert the array of users to User objects
			$roles = array_map(function ($role) {
				return new Role($role["id"], $role["name"]);
			}, $roles);

			return $roles;
		} catch (PDOException $e) {
			error_log("Error getting products: {$e->getMessage()}");
			return [];
		}
	}
};
