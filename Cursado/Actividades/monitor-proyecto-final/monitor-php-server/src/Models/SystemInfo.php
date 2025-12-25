<?php

namespace App\Models;

use DateTime;

class SystemInfo
{

	public string $hostname;
	public string $os;
	public string $uptime;
	public string $cpuModel;
	public int $cpuCores;
	public DateTime $timestamp;

	public function __construct(string $hostname, string $os, string $uptime, string $cpuModel, int $cpuCores, DateTime $timestamp)
	{
		$this->hostname = $hostname;
		$this->os = $os;
		$this->uptime = $uptime;
		$this->cpuModel = $cpuModel;
		$this->cpuCores = $cpuCores;
		$this->timestamp = $timestamp;
	}
}
