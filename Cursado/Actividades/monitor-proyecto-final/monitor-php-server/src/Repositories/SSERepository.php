<?php

namespace App\Repositories;

use App\Models\Role;
use App\Models\SSEEvent;
use Exception;
use PDO;

class SSERepository
{
	public function __construct(private PDO $pdo)
	{
		$this->pdo = $pdo;
	}

	public function addUserState(int $userId): bool
	{
		$sql = "INSERT IGNORE INTO sse_user_states (user_id) VALUES (:userId)";
		try {
			$stmt = $this->pdo->prepare($sql);
			$success = $stmt->execute(['userId' => $userId]);

			return $success;
		} catch (Exception $e) {
			error_log("Error adding SSE connection: {$e->getMessage()}");
			return false;
		}
	}

	public function updateUserState(int $userId, int $lastEventId): bool
	{
		$sql = "UPDATE sse_user_states SET last_event_id = :lastEventId WHERE user_id = :userId";
		try {
			$stmt = $this->pdo->prepare($sql);
			$success = $stmt->execute([
				'lastEventId' => $lastEventId,
				'userId' => $userId
			]);

			return $success;
		} catch (Exception $e) {
			error_log("Error updating user SSE state: {$e->getMessage()}");
			return false;
		}
	}

	public function enqueueEvent(SSEEvent $sseEvent): bool
	{
		$sql = "INSERT INTO sse_events_queue (motive, data) VALUES (:motive, :data)";
		try {
			$stmt = $this->pdo->prepare($sql);
			$success = $stmt->execute([
				'motive' => $sseEvent->motive,
				'data' => $sseEvent->data
			]);

			return $success;
		} catch (Exception $e) {
			error_log("Error enqueuing SSE event: {$e->getMessage()}");
			return false;
		}
	}

	public function getPendingEvents(int $userId): array
	{
		$sql = "SELECT id, motive, data FROM sse_events_queue WHERE id > (SELECT last_event_id FROM sse_user_states WHERE user_id = :userId) ORDER BY id ASC";
		try {
			$stmt = $this->pdo->prepare($sql);
			$stmt->execute(['userId' => $userId]);
			$pendingEvents = $stmt->fetchAll();

			if (count($pendingEvents) < 1) {
				return [];
			}
			error_log($userId);
			error_log(json_encode($pendingEvents));
			return array_map(function ($event) {
				return new SSEEvent(
					$event["motive"],
					$event["data"],
					$event["id"],
				);
			}, $pendingEvents);
		} catch (Exception $e) {
			error_log("Error fetching pending SSE events: {$e->getMessage()}");
			return [];
		}
	}
}
