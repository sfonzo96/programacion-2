<?php

namespace App\Database;

use PDO;
use PDOException;
use RuntimeException;

class Database
{
	private static ?Database $instance = null;
	private PDO $pdo;

	private function __construct(string $dsn, string $username, string $password, array $options = [])
	{
		try {
			$this->pdo = new PDO($dsn, $username, $password, $options);
		} catch (PDOException $e) {
			throw new RuntimeException("Error de conexión: {$e->getMessage()}");
		}
	}

	// Comment: Singleton but PHP is stateless and not resource shared, which makes it unnecessary
	public static function getInstance(
		?string $dsn = null,
		?string $username = null,
		?string $password = null,
		?array $options = []
	): Database {
		if (self::$instance === null) {
			self::$instance = new self($dsn, $username, $password, $options);
		}
		return self::$instance;
	}

	public function getConnection(): PDO
	{
		return $this->pdo;
	}
}
