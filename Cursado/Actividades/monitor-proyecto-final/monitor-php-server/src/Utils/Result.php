<?php


namespace App\Utils;

use App\Enums\HttpStatusCodes;
use App\Enums\ResponseMessages;

class Result
{
	public readonly bool $isSuccess;
	public readonly int $code;
	public readonly ResponseMessages|string $message;
	public readonly mixed $data;
	public readonly ?string $redirectTo;

	private function __construct(bool $isSuccess, HttpStatusCodes $code, ResponseMessages|string $message, mixed $data = null, ?string $redirectTo = null)
	{
		$this->isSuccess = $isSuccess;
		$this->code = $code->value;
		$this->message = $message;
		$this->data = $data;
		$this->redirectTo = $redirectTo;
	}

	public static function Success(HttpStatusCodes $code = HttpStatusCodes::ACCEPTED, ResponseMessages|string  $message = ResponseMessages::OK, mixed $data = null, ?string $redirectTo = null)
	{
		return new self(true, $code, $message, $data, $redirectTo);
	}

	public static function Failure(HttpStatusCodes $code = HttpStatusCodes::INTERNAL_SERVER_ERROR, ResponseMessages|string $errorMessage = ResponseMessages::INTERNAL_SERVER_ERROR, mixed $data = null, ?string $exMessage = null): self
	{
		if ($exMessage) {
			error_log($exMessage);
		}
		return new self(false, $code, $errorMessage, $data);
	}
}
