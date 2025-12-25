<?php

namespace App\Repositories;

use App\DTOs\Requests\CreateHostRequest;
use App\Models\Host;
use App\Models\Network;
use App\Utils\NetworksUtils;
use DateTime;
use Exception;
use PDO;

class HostsRepository
{
	private PDO $pdo;
	public function __construct(PDO $pdo)
	{
		$this->pdo = $pdo;
	}

	public function create(CreateHostRequest $host): bool
	{
		$sql = "INSERT INTO hosts (ip_address, mac_address, network_id, hostname) VALUES (:ip_address, :mac_address, :network_id, :hostname);";

		try {
			$stmt = $this->pdo->prepare($sql);
			$success = $stmt->execute([
				":ip_address" => $host->ipAddress,
				":mac_address" => $host->macAddress,
				":network_id" => $host->networkId,
				":hostname" => $host->hostname,
			]);

			if (!$success) {
				return false;
			}

			return true;
		} catch (Exception $e) {
			error_log("Error creating host: {$e->getMessage()}");
			return false;
		}
	}

	public function getAll(): array
	{
		$sql = "SELECT 
					h.id,
					h.ip_address,
					h.hostname,
					h.mac_address,
					h.last_seen,
					h.first_seen,
					h.is_online,
					n.id AS network_id,
					n.ip_address AS network_ip_address,
					n.cidr_mask AS network_cidr_mask,
					n.description AS network_description
				FROM hosts AS h
				LEFT JOIN networks AS n ON h.network_id = n.id;";

		try {
			$stmt = $this->pdo->query($sql);
			$hosts = $stmt->fetchAll();

			if (count($hosts) == 0) {
				return [];
			}

			$hosts = array_map(function ($host) {
				$network = null;
				if ($host['network_id']) {

					$isNetworkOnline = NetworksUtils::isNetworkOnline($host['network_ip_address'], $host['network_cidr_mask']);

					$network = new Network(
						$host['network_id'],
						$host['network_description'],
						$host['network_ip_address'],
						$host['network_cidr_mask'],
						$isNetworkOnline
					);
				}

				return new Host(
					$host['id'],
					$host['hostname'],
					$host['mac_address'],
					$host['ip_address'],
					(bool)$host['is_online'],
					new DateTime($host['first_seen']),
					new DateTime($host['last_seen']),
					$network
				);
			}, $hosts);

			return $hosts;
		} catch (Exception $e) {
			error_log("Error getting all hosts: {$e->getMessage()}");
			return [];
		}
	}

	public function getByMac(string $macAddress): ?Host
	{
		$sql = "SELECT 
					h.id,
					h.ip_address,
					h.hostname,
					h.mac_address,
					h.last_seen,
					h.first_seen,
					h.is_online,
					n.id AS network_id,
					n.ip_address AS network_ip_address,
					n.cidr_mask AS network_cidr_mask,
					n.description AS network_description
				FROM hosts AS h
				JOIN networks AS n ON h.network_id = n.id
				WHERE h.mac_address = :mac_address;";

		try {
			$stmt = $this->pdo->prepare($sql);
			$stmt->execute([
				":mac_address" => $macAddress
			]);

			$host = $stmt->fetch();
			if (!$host) {
				return null;
			}

			$isNetworkOnline = NetworksUtils::isNetworkOnline($host['network_ip_address'], $host['network_cidr_mask']);

			return new Host(
				$host['id'],
				$host['hostname'],
				$host['mac_address'],
				$host['ip_address'],
				(bool)$host['is_online'],
				new DateTime($host['first_seen']),
				new DateTime($host['last_seen']),
				new Network(
					$host['network_id'],
					$host['network_description'],
					$host['network_ip_address'],
					$host['network_cidr_mask'],
					$isNetworkOnline
				)
			);
		} catch (Exception $e) {
			error_log("Error getting host by MAC address: {$e->getMessage()}");
			return null;
		}
	}

	public function getById(int $hostId): ?Host
	{
		$sql = "SELECT 
					h.id,
					h.ip_address,
					h.hostname,
					h.mac_address,
					h.last_seen,
					h.first_seen,
					h.is_online,
					n.id AS network_id,
					n.ip_address AS network_ip_address,
					n.cidr_mask AS network_cidr_mask,
					n.description AS network_description
				FROM hosts AS h
				JOIN networks AS n ON h.network_id = n.id
				WHERE h.id = :host_id;";

		try {
			$stmt = $this->pdo->prepare($sql);
			$stmt->execute([
				":host_id" => $hostId
			]);

			$host = $stmt->fetch();

			$isNetworkOnline = NetworksUtils::isNetworkOnline($host['network_ip'], $host['network_cidr_mask']);

			return new Host(
				$host['id'],
				$host['hostname'],
				$host['mac_address'],
				$host['ip_address'],
				(bool)$host['is_online'],
				new DateTime($host['first_seen']),
				new DateTime($host['last_seen']),
				new Network(
					$host['network_id'],
					$host['network_description'],
					$host['network_ip_address'],
					$host['network_cidr_mask'],
					$isNetworkOnline
				)
			);
		} catch (Exception $e) {
			error_log("Error getting host by id: {$e->getMessage()}");
			return null;
		}
	}

	public function getHostsByNetworkId(int $networkId): array
	{
		$sql = "SELECT 
					h.id,
					h.ip_address,
					h.hostname,
					h.mac_address,
					h.last_seen,
					h.first_seen,
					h.is_online,
					n.id AS network_id,
					n.ip_address AS network_ip_address,
					n.cidr_mask AS network_cidr_mask,
					n.description AS network_description
				FROM hosts AS h
				JOIN networks AS n ON h.network_id = n.id
				WHERE h.network_id = :network_id;";

		try {
			$stmt = $this->pdo->prepare($sql);
			$stmt->execute([
				":network_id" => $networkId
			]);

			$hosts = $stmt->fetchAll();

			if (count($hosts) == 0) {
				return [];
			}

			$hosts = array_map(function ($host) {
				$isNetworkOnline = NetworksUtils::isNetworkOnline($host['network_ip_address'], $host['network_cidr_mask']);

				return new Host(
					$host['id'],
					$host['hostname'],
					$host['mac_address'],
					$host['ip_address'],
					(bool)$host['is_online'],
					new DateTime($host['first_seen']),
					new DateTime($host['last_seen']),
					new Network(
						$host['network_id'],
						$host['network_description'],
						$host['network_ip_address'],
						$host['network_cidr_mask'],
						$isNetworkOnline
					)
				);
			}, $hosts);

			return $hosts;
		} catch (Exception $e) {
			error_log("Error getting hosts by network ID: {$e->getMessage()}");
			return [];
		}
	}
}
