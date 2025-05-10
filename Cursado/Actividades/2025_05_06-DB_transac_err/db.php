<?php
// singleton?
namespace Data;

use PDO;
use PDOException;

class Database
{
	private static string $host = 'localhost';
	private static string $port = '3306';
	private static string $dbname = 'prog2_practica';
	private static string $charset = 'utf8mb4';
	private static string $username = 'root';
	private static string $password = 'notmyrealpassword';
	private static ?PDO $pdo = null;

	public function __construct() {}

	public static function getPDOInstance()
	{
		if (self::$pdo == null) {
			$dsn = "mysql:host=" . self::$host . ";port=" . self::$port . ";dbname=" . self::$dbname . ";charset=" . self::$charset;
			$options = [
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Por qué array asociativo y no de objetos?
				PDO::ATTR_EMULATE_PREPARES => false,
			];

			try {
				self::$pdo = new PDO($dsn, self::$username, self::$password, $options);
				print("Connected to database " . self::$dbname . " at " . self::$host . ":" . self::$port . " successful." . "\n");
			} catch (PDOException $e) {
				error_log($e->getMessage(), $e->getCode());
				exit("Error connecting to database " . self::$dbname . " at " . self::$host . ":" . self::$port . "\n");
			}
		}

		return self::$pdo;
	}
}
