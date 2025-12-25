<?php

namespace App\Models;

use DateTime;

class Report
{
	public int $id;
	public Event $event;
	public DateTime $createdAt;
	public string $details;

	public function __construct(
		int $id,
		Event $event,
		DateTime $createdAt,
		string $details
	) {
		$this->id = $id;
		$this->event = $event;
		$this->createdAt = $createdAt;
		$this->details = $details;
	}
}
