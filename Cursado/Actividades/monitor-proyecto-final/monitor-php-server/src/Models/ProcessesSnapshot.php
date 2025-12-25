<?php

namespace App\Models;

use DateTime;

class ProcessesSnapshot
{
	public DateTime $timestamp;
	public string $output;

	public function __construct(string $output)
	{
		$this->timestamp = new DateTime();
		$this->output = $output;
	}
}
