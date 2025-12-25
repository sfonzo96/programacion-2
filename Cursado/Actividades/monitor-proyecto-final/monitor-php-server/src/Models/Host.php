<?php

namespace App\Models;

use DateTime;

class Host
{
	public int $id;
	public string $hostname;
	public string $macAddress;
	public string $ipAddress;
	public DateTime $firstSeen;
	public DateTime $lastSeen;
	public bool $isOnline;
	public ?Network $network;

	public function __construct(
		int $id,
		string $hostname,
		string $macAddress,
		string $ipAddress,
		bool $isOnline,
		DateTime $firstSeen,
		DateTime $lastSeen,
		?Network $network = null,
	) {
		$this->id = $id;
		$this->hostname = $hostname;
		$this->macAddress = $macAddress;
		$this->ipAddress = $ipAddress;
		$this->firstSeen = $firstSeen;
		$this->lastSeen = $lastSeen;
		$this->isOnline = $isOnline;
		$this->network = $network;
	}
}
