<?php

namespace App\Utils;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class RefreshToken
{
	public string $username;
	public string $value;

	public function __construct(string $username, string $value)
	{
		$this->username = $username;
		$this->value = $value;
	}
}

class JWTUtils
{
	private static string $refreshStoragePath = __DIR__ . "/../Cache/refresh_tokens.json";

	public static function getUserSavedToken(string $username): ?RefreshToken
	{
		$tokens = self::getTokens();
		foreach ($tokens as $token) {
			if ($token->username === $username) {
				return $token;
			}
		}
		return null;
	}

	public static function tokenIsExpired(int $unixTokenExp): bool
	{
		return $unixTokenExp < time();
	}

	public static function decode(string $token): object
	{
		return JWT::decode($token, new Key($_ENV["JWT_SECRET"], 'HS256'));
	}

	public static function encode(object|array $payload): string
	{
		return JWT::encode($payload, $_ENV["JWT_SECRET"]);
	}

	private static function getTokens(): array
	{
		if (!file_exists(self::$refreshStoragePath)) {
			file_put_contents(self::$refreshStoragePath, json_encode([]));
			return [];
		}

		$data = json_decode(file_get_contents(self::$refreshStoragePath), true) ?? [];
		return array_map(function ($token) {
			return new RefreshToken($token["username"], $token["value"]);
		}, $data);
	}

	public static function saveToken(string $username, string $value): void
	{
		$tokens = self::getTokens();

		$index = null;
		foreach ($tokens as $ix => $token) {
			if ($token->username === $username) {
				$index = $ix;
				break;
			}
		}

		if ($index !== null) {
			$tokens[$index]->value = $value; // Comment: Updates token if already exists
		} else {
			$tokens[] = new RefreshToken($username, $value); // Comment: Adds if it doesn't
		}

		file_put_contents(self::$refreshStoragePath, json_encode($tokens));
	}

	public static function removeToken(string $username): void
	{
		$tokens = self::getTokens();
		$tokens = array_filter($tokens, function (RefreshToken $token) use ($username) {
			return $token->username != $username;
		});

		file_put_contents(self::$refreshStoragePath, json_encode($tokens));
	}
}
