<?php

namespace App\Middlewares;

use App\Attributes\Required;
use App\Enums\HttpStatusCodes;
use App\Enums\ResponseMessages;
use App\Utils\ApiResponder;
use App\Utils\Result;
use Psr\Http\Message\ResponseFactoryInterface as IResponseFactory;
use Psr\Http\Server\MiddlewareInterface as IMiddleware;
use Psr\Http\Server\RequestHandlerInterface as IHandler;
use Psr\Http\Message\ResponseInterface as IResponse;
use Psr\Http\Message\ServerRequestInterface as IRequest;
use ReflectionClass;

class RequestBodyValidatorMiddleware implements IMiddleware
{
	private string $requestBodyClass;
	private IResponseFactory $responseFactory;
	private static array $cachedReflections = [];

	public function __construct(string $requestBodyClass, IResponseFactory $responseFactory)
	{
		$this->responseFactory = $responseFactory;
		$this->requestBodyClass = $requestBodyClass;
	}

	public function process(IRequest $request, IHandler $handler): IResponse
	{
		$body = $request->getParsedBody() ?? [];
		$errors = $this->validateBody($body, $this->requestBodyClass);

		if (!empty($errors)) {
			return ApiResponder::Respond($this->responseFactory->createResponse(), Result::Failure(HttpStatusCodes::BAD_REQUEST, ResponseMessages::BAD_REQUEST, ["errors" => $errors]));
		}

		return $handler->handle($request);
	}

	private function validateBody(array $body, string $class): array
	{
		$errors = [];
		if (!array_key_exists($class, self::$cachedReflections)) {
			self::$cachedReflections[$class] = new ReflectionClass($class);
		}
		$reflection = self::$cachedReflections[$class];

		foreach ($reflection->getProperties() as $property) {
			$propertyName = $property->getName();
			$propertyType = $property->getType();
			$hasValue = array_key_exists($propertyName, $body);
			$isRequired = in_array(Required::class, array_map(fn($attr) => $attr->getName(), $property->getAttributes()));

			$propertyValue = $hasValue ? $body[$propertyName] : null;

			if (!$hasValue) {
				if ($isRequired) $errors[$propertyName][] = "Required";
				continue;
			}

			// Comment: Type checking
			if ($propertyType && $hasValue) {
				$typeName = $propertyType->getName();

				switch ($typeName) {
					case 'int':
						if (!is_int($propertyValue)) $errors[$propertyName][] = "Must be an integer";
						break;
					case 'string':
						if (!is_string($propertyValue)) $errors[$propertyName][] = "Must be a string";
						break;
					case 'float':
						// Comment: if 25.0 is parsed as 25 it should be allowed
						if (!is_float($propertyValue) && !is_int($propertyValue)) $errors[$propertyName][] = "Must be a float";
						break;
					case 'bool':
						if (!is_bool($propertyValue)) $errors[$propertyName][] = "Must be a bool";
						break;
					case 'array':
						if (!is_array($propertyValue)) $errors[$propertyName][] = "Must be an array";
						break;
				};
			}

			// Comment: Attribute compliance checking
			foreach ($property->getAttributes() as $attribute) {
				$attributeInstance = $attribute->newInstance();

				$errorMsg = $attributeInstance->validate($propertyValue);
				if ($errorMsg) {
					$errors[$propertyName][] = $errorMsg;
				}
			}
		}

		return $errors;
	}
}
