<?php

namespace App\Models;

class SSEEvent
{
	public ?int $id;
	public string $motive;
	public string $data;

	public function __construct(
		string $motive,
		string $data,
		?int $id = null,
	) {
		$this->id = $id;
		$this->motive = $motive;
		$this->data = $data;
	}
}
