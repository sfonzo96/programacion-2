<?php

namespace Utils;

class Result
{
	public readonly bool $isSuccess;
	public readonly int $code;
	public readonly ?string $message;
	public readonly mixed $data;

	private function __construct(bool $isSuccess, HttpStatusCodes $code, ?string $message = null, mixed $data = null)
	{
		$this->isSuccess = $isSuccess;
		$this->code = $code->value;
		$this->message = $message;
		$this->data = $data;
	}

	public static function Success(HttpStatusCodes $code = HttpStatusCodes::ACCEPTED, ?string $message = null, mixed $data = null): self
	{
		return new self(true, $code, $message, $data);
	}

	public static function Failure(HttpStatusCodes $code = HttpStatusCodes::INTERNAL_SERVER_ERROR, string $errorMessage = "Oops, something happened..."): self
	{
		return new self(false, $code, $errorMessage);
	}
}
