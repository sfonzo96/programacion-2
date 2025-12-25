<?php

namespace App\Repositories;

use App\DTOs\Requests\CreateMetricRecordRequest;
use App\Models\Metric;
use App\Models\MetricRecord;
use DateTime;
use Dom\Mysql;
use Exception;
use PDO;

class MetricsRepository
{
	private PDO $pdo;
	public function __construct(PDO $pdo)
	{
		$this->pdo = $pdo;
	}

	public function create(CreateMetricRecordRequest $metricRecord): bool
	{
		$sql = "INSERT INTO metrics_records (metric_id, value) VALUES (:metric_id, :value)";
		try {
			$stmt = $this->pdo->prepare($sql);

			$success = $stmt->execute([
				":metric_id" => $metricRecord->metricId,
				":value" => $metricRecord->value,
			]);
			if (!$success) {
				return false;
			}

			return true;
		} catch (Exception $ex) {
			error_log("Error creating metric record: {$ex->getMessage()}");
			return false;
		}
		return false;
	}

	public function getAll(): array
	{
		$sql = "SELECT id, name, description, measure_unit_format FROM metrics;";
		try {
			$stmt = $this->pdo->prepare($sql);
			$stmt->execute();

			$metrics = $stmt->fetchAll();
			if (!$metrics) {
				return [];
			}

			return array_map(function ($metric) {
				return new Metric(
					$metric["id"],
					$metric["name"],
					$metric["description"],
					$metric["measure_unit_format"]
				);
			}, $metrics);
		} catch (Exception $ex) {
			error_log("Error fetching metrics: {$ex->getMessage()}");
			return [];
		}
	}

	public function getMetricById(int $metricId): ?Metric
	{
		$sql = "SELECT id, name, description, measure_unit_format FROM metrics WHERE id = :id;";
		try {
			$stmt = $this->pdo->prepare($sql);
			$stmt->execute([
				":id" => $metricId
			]);

			$metric = $stmt->fetch();
			if (!$metric) {
				return null;
			}

			return new Metric(
				$metric["id"],
				$metric["name"],
				$metric["description"],
				$metric["measure_unit_format"]
			);
		} catch (Exception $ex) {
			error_log("Error fetching metric by ID: {$ex->getMessage()}");
			return null;
		}
	}

	public function getMetricWithRecordsById(int $metricId, ?int $since): ?Metric
	{
		$metric = $this->getMetricById($metricId);
		if (!$metric) {
			return null;
		}
		error_log($since);
		$sql = "SELECT created_at, value FROM metrics_records WHERE metric_id = :metric_id AND UNIX_TIMESTAMP(created_at) * 1000 >= :since ORDER BY created_at DESC";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([
			":metric_id" => $metricId,
			":since" => $since
		]);

		$records = $stmt->fetchAll();
		if (!$records) {
			return null;
		}

		$records = array_map(function ($record) use ($metric) {
			return new MetricRecord(
				$metric->id,
				$record["value"],
				new DateTime($record["created_at"])
			);
		}, $records);

		$metric->records = $records;

		return $metric;
	}
}
