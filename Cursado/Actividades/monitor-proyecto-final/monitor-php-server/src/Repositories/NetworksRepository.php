<?php

namespace App\Repositories;

use App\DTOs\Requests\CreateNetworkRequest;
use App\Models\Network;
use App\Utils\NetworksUtils;
use PDO;
use PDOException;

class NetworksRepository
{

	private PDO $pdo;

	public function __construct(PDO $pdo)
	{
		$this->pdo = $pdo;
	}

	public function create(CreateNetworkRequest $networkData): bool
	{
		try {
			$existingNetwork = $this->getbyIpAndMask($networkData->ipAddress, $networkData->CIDRMask);
			if ($existingNetwork !== null) {
				return false;
			}
		} catch (PDOException $ex) {
			error_log("Error checking existing network: {$ex->getMessage()}");
			return false;
		}

		$sql = "INSERT IGNORE INTO networks (ip_address, cidr_mask, description) VALUES (:ip_address, :cidr_mask, :description);";

		try {
			$stmt = $this->pdo->prepare($sql);
			$success = $stmt->execute([
				":ip_address" => $networkData->ipAddress,
				":cidr_mask" => $networkData->CIDRMask,
				":description" => $networkData->description
			]);

			if (!$success) {
				return false;
			}

			return true;
		} catch (PDOException $e) {
			error_log("Error creating network: {$e->getMessage()}");
			return false;
		}
		return false;
	}

	public function getAll(): array
	{
		$sql = "SELECT 
					id,
					ip_address,
					cidr_mask,
					description
				FROM networks AS n";

		try {
			$stmt = $this->pdo->query($sql);
			$networks = $stmt->fetchAll();

			if (count($networks) == 0) {
				return [];
			}

			$networks = array_map(function ($network) {
				$isOnline = NetworksUtils::isNetworkOnline($network['ip_address'], $network['cidr_mask']);

				return new Network(
					$network['id'],
					$network['description'],
					$network['ip_address'],
					$network['cidr_mask'],
					$isOnline
				);
			}, $networks);

			return $networks;
		} catch (PDOException $e) {
			error_log("Error getting products: {$e->getMessage()}");
			return [];
		}
	}

	public function getById(int $id): ?Network
	{
		$sql = "SELECT 
					id,
					ip_address,
					cidr_mask,
					description
				FROM networks AS n
				WHERE n.id = :id;";

		try {
			$stmt = $this->pdo->prepare($sql);
			$stmt->execute([
				":id" => $id
			]);

			$network = $stmt->fetch();

			if (!$network) {
				return null;
			}

			$isOnline = NetworksUtils::isNetworkOnline($network['ip_address'], $network['cidr_mask']);

			return new Network(
				$network["id"],
				$network["description"],
				$network["ip_address"],
				$network["cidr_mask"],
				$isOnline
			);
		} catch (PDOException $e) {
			error_log("Error finding network: {$e->getMessage()}");
			return null;
		}
	}

	public function getbyIpAndMask(string $ipAddress, string $cidrMask): ?Network
	{
		$sql = "SELECT 
					id,
					ip_address,
					cidr_mask,
					description
				FROM networks AS n
				WHERE n.ip_address = :ip_address AND n.cidr_mask = :cidr_mask;";

		try {
			$stmt = $this->pdo->prepare($sql);
			$stmt->execute([
				":ip_address" => $ipAddress,
				":cidr_mask" => $cidrMask
			]);

			$network = $stmt->fetch();

			if (!$network) {
				return null;
			}

			$isOnline = NetworksUtils::isNetworkOnline($network['ip_address'], $network['cidr_mask']);

			return new Network(
				$network["id"],
				$network["description"],
				$network["ip_address"],
				$network["cidr_mask"],
				$isOnline
			);
		} catch (PDOException $e) {
			error_log("Error finding network: {$e->getMessage()}");
			return null;
		}
	}

	public function deleteById(int $id): bool
	{
		$sql = "DELETE FROM networks WHERE id = :id;";

		try {
			$stmt = $this->pdo->prepare($sql);
			$success = $stmt->execute([
				":id" => $id
			]);

			if (!$success) {
				return false;
			}

			return true;
		} catch (PDOException $e) {
			error_log("Error deleting network: {$e->getMessage()}");
			return false;
		}
	}
}
