<?php

namespace App\Services;

use App\Models\SSEEvent;
use DateTime;
use App\Repositories\SSERepository;

class SSEService
{
	private SSERepository $sseRepository;

	public function __construct(SSERepository $sseRepository)
	{
		$this->sseRepository = $sseRepository;
	}

	public function sendEvent(string $event, string $encoded_data, ?int $eventId = null): void
	{
		// Comment: SSE uses text only so data format should be json
		$output = "event: $event\n";
		$output .= "data: $encoded_data\n";
		if ($eventId !== null) {
			$output .= "id: $eventId\n\n";
		}
		echo $output;

		if (ob_get_level()) {
			ob_flush(); // Comment: Flushes the output buffer to the client
		}
		flush();
	}

	public function enqueueEvent(SSEEvent $event): void
	{
		$this->sseRepository->enqueueEvent($event);
	}

	public function getPendingEvents(int $userId): array
	{
		return $this->sseRepository->getPendingEvents($userId);
	}

	public function addUserState(int $userId): void
	{
		$this->sseRepository->addUserState($userId);
	}

	public function updateUserState(int $userId, int $lastEventId): void
	{
		$this->sseRepository->updateUserState($userId, $lastEventId);
	}

	public function sendHeartbeat(): void
	{
		$this->sendEvent('heartbeat', json_encode([
			'timestamp' => (new DateTime())->format('Y-m-d H:i:s')
		]));
	}
}
