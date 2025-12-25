i# Validación de Datos con Middleware, Reflexión y Atributos en PHP

Este documento explica cómo implementar un sistema de validación automática de datos utilizando **middleware**,
**reflexión** y **atributos** de PHP 8+. El sistema permite validar automáticamente el cuerpo de las peticiones HTTP
antes de que lleguen a los controladores.

---

## Conceptos Clave

| Concepto       | Definición                                                                          |
| -------------- | ----------------------------------------------------------------------------------- |
| **Middleware** | Componente que intercepta peticiones HTTP antes de llegar al controlador            |
| **Reflexión**  | Capacidad de PHP para examinar clases, propiedades y métodos en tiempo de ejecución |
| **Atributos**  | Metadatos que se pueden agregar a clases, propiedades y métodos (PHP 8+)            |
| **DTO**        | Data Transfer Object - Objeto que define la estructura de datos esperada            |

---

## Implementación de Atributos de Validación

### Interfaz Base para Atributos

Todos los atributos de validación implementan una interfaz común:

```php
<?php

namespace App\Attributes;

interface IAttribute
{
    public function validate(mixed $value): ?string;
}
```

### Atributo Required

Valida que un campo sea obligatorio:

```php
<?php

namespace App\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Required implements IAttribute
{
    public function validate(mixed $value): ?string
    {
        if (empty($value)) {
            return "Value is required.";
        }
        return null;
    }
}
```

### Atributos de Longitud

Validación de longitud mínima y máxima de cadenas:

```php
<?php

namespace App\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class MinLength implements IAttribute
{
    public int $length;

    public function __construct(int $length)
    {
        $this->length = $length;
    }

    public function validate(mixed $value): ?string
    {
        if (strlen((string)$value) < $this->length) {
            return "Min length not reached";
        }
        return null;
    }
}

#[Attribute(Attribute::TARGET_PROPERTY)]
class MaxLength implements IAttribute
{
    public int $length;

    public function __construct(int $length)
    {
        $this->length = $length;
    }

    public function validate(mixed $value): ?string
    {
        if (strlen((string)$value) > $this->length) {
            return "Max length exceeded";
        }
        return null;
    }
}
```

### Atributo Email

Valida formato de email:

```php
<?php

namespace App\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Email implements IAttribute
{
    public function validate(mixed $value): ?string
    {
        if (filter_var($value, FILTER_VALIDATE_EMAIL) === false) {
            return "Invalid email format.";
        }
        return null;
    }
}
```

---

## DTOs con Atributos

Los DTOs definen la estructura de datos esperada y sus reglas de validación:

### Ejemplo: CreateUserRequest

```php
<?php

namespace App\DTOs\Requests;

use App\Attributes\MaxLength;
use App\Attributes\MinLength;
use App\Attributes\Required;

class CreateUserRequest
{
    #[Required]
    #[MinLength(5)]
    public string $firstName;

    #[Required]
    #[MinLength(5)]
    public string $lastName;

    #[Required]
    #[MinLength(5)]
    #[MaxLength(20)]
    public string $username;

    #[Required]
    #[MinLength(8)]
    #[MaxLength(20)]
    public string $password;

    #[Required]
    #[MinLength(8)]
    #[MaxLength(20)]
    public string $confirmPassword;

    public function __construct(string $firstName, string $lastName, string $username, string $password)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->username = $username;
        $this->password = $password;
    }
}
```

### Ejemplo: CreateHostRequest

```php
<?php

namespace App\DTOs\Requests;

use App\Attributes\MaxLength;
use App\Attributes\Numeric;
use App\Attributes\Required;

class CreateHostRequest
{
    #[Required]
    #[MaxLength(15)]
    public string $ipAddress;

    #[Required]
    #[MaxLength(17)]
    public string $macAddress;

    #[Required]
    #[Numeric]
    public int $networkId;

    #[MaxLength(255)]
    public ?string $hostname;

    public function __construct(string $ipAddress, string $macAddress, int $networkId, ?string $hostname)
    {
        $this->ipAddress = $ipAddress;
        $this->macAddress = $macAddress;
        $this->networkId = $networkId;
        $this->hostname = $hostname;
    }
}
```

---

## Cómo Funciona la Reflexión

### Análisis de Propiedades

La reflexión permite examinar la clase en tiempo de ejecución:

```php
// Crear una instancia de ReflectionClass
$reflection = new ReflectionClass($class);

// Obtener todas las propiedades de la clase
foreach ($reflection->getProperties() as $property) {
    $propertyName = $property->getName();           // Nombre de la propiedad
    $propertyType = $property->getType();           // Tipo de la propiedad
    $attributes = $property->getAttributes();        // Atributos aplicados
}
```

### Procesamiento de Atributos

```php
// Verificar si tiene atributo Required
$isRequired = in_array(Required::class, array_map(fn($attr) => $attr->getName(), $property->getAttributes()));

// Validar con cada atributo
foreach ($property->getAttributes() as $attribute) {
    $attributeInstance = $attribute->newInstance();  // Crear instancia del atributo
    $errorMsg = $attributeInstance->validate($propertyValue);  // Ejecutar validación
}
```

---

## Middleware de Validación

### Implementación Completa

El middleware utiliza **reflexión** para examinar las propiedades del DTO y sus atributos:

```php
<?php

namespace App\Middlewares;

use App\Attributes\Required;
use Psr\Http\Server\MiddlewareInterface as IMiddleware;
use Psr\Http\Server\RequestHandlerInterface as IHandler;
use Psr\Http\Message\ResponseInterface as IResponse;
use Psr\Http\Message\ServerRequestInterface as IRequest;
use ReflectionClass;

class RequestBodyValidatorMiddleware implements IMiddleware
{
    private string $requestBodyClass;
    private static array $cachedReflections = [];

    public function __construct(string $requestBodyClass)
    {
        $this->requestBodyClass = $requestBodyClass;
    }

    public function process(IRequest $request, IHandler $handler): IResponse
    {
        $body = $request->getParsedBody() ?? [];
        $errors = $this->validateBody($body, $this->requestBodyClass);

        if (!empty($errors)) {
            $response = new \Slim\Psr7\Response();
            $response->getBody()->write(json_encode([
                "status" => "error",
                "message" => "Validation failed",
                "errors" => $errors
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
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

            // Validar campos requeridos
            if (!$hasValue) {
                if ($isRequired) $errors[$propertyName][] = "Required";
                continue;
            }

            // Validación de tipos
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
                        if (!is_float($propertyValue) && !is_int($propertyValue)) $errors[$propertyName][] = "Must be a float";
                        break;
                    case 'bool':
                        if (!is_bool($propertyValue)) $errors[$propertyName][] = "Must be a bool";
                        break;
                    case 'array':
                        if (!is_array($propertyValue)) $errors[$propertyName][] = "Must be an array";
                        break;
                }
            }

            // Validación con atributos personalizados
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
```

---

## Uso del Middleware en Rutas

### Registro en Slim Framework

El middleware se registra directamente en las rutas que requieren validación:

```php
<?php

use App\DTOs\Requests\CreateUserRequest;
use App\DTOs\Requests\UpdateUserPasswordRequest;
use App\Middlewares\RequestBodyValidatorMiddleware;
use Slim\Factory\AppFactory;

$app = AppFactory::create();

// Ruta para crear usuario con validación
$app->post("/api/users", function ($request, $response) {
    $body = $request->getParsedBody();
    // Lógica para crear usuario
    $response->getBody()->write(json_encode(["message" => "Usuario creado exitosamente"]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
})->add(new RequestBodyValidatorMiddleware(CreateUserRequest::class));

// Ruta para actualizar contraseña con validación
$app->patch("/api/users/password", function ($request, $response) {
    $body = $request->getParsedBody();
    // Lógica para actualizar contraseña
    $response->getBody()->write(json_encode(["message" => "Contraseña actualizada"]));
    return $response->withHeader('Content-Type', 'application/json');
})->add(new RequestBodyValidatorMiddleware(UpdateUserPasswordRequest::class));
```

### Ejemplo con Múltiples Middlewares

```php
$app->post("/api/networks", function ($request, $response) {
    // Lógica para crear red
    $response->getBody()->write(json_encode(["message" => "Red creada exitosamente"]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
})
->add(new RequestBodyValidatorMiddleware(CreateNetworkRequest::class))
->add(new RoleAuthorizationMiddleware([UsersRoles::ADMIN, UsersRoles::MANAGER]));
```

---

## Ventajas del Sistema

### 1. **Automatización**

-   Las validaciones se ejecutan automáticamente
-   No necesidad de validación manual en cada controlador

### 2. **Reutilización**

-   Los atributos se pueden reutilizar en múltiples DTOs
-   El middleware funciona con cualquier DTO

### 3. **Mantenibilidad**

-   Validaciones declarativas en los DTOs
-   Fácil agregar nuevos tipos de validación

### 4. **Flexibilidad**

-   Combinar múltiples atributos en una propiedad
-   Validaciones complejas mediante atributos personalizados

---

## Flujo de Validación

1. **Petición HTTP** llega al servidor
2. **Middleware** intercepta la petición
3. **Reflexión** examina el DTO especificado
4. **Atributos** se procesan para cada propiedad
5. **Validaciones** se ejecutan automáticamente
6. **Errores** se recolectan y devuelven como JSON si existen
7. **Función de ruta** se ejecuta solo si la validación pasa

---

## Conclusión

Este sistema combina las características modernas de PHP (atributos, reflexión) con el patrón middleware para crear una
solución elegante y eficiente de validación de datos. La separación de responsabilidades y la naturaleza declarativa de
las validaciones hace que el código sea más limpio, mantenible y escalable.

---

### Referencias

-   PHP Manual: Attributes. Recuperado de
    [https://www.php.net/manual/en/language.attributes.php](https://www.php.net/manual/en/language.attributes.php)
-   PHP Manual: Reflection. Recuperado de
    [https://www.php.net/manual/en/book.reflection.php](https://www.php.net/manual/en/book.reflection.php)
-   Slim Framework: Middleware. Recuperado de
    [https://www.slimframework.com/docs/v4/concepts/middleware.html](https://www.slimframework.com/docs/v4/concepts/middleware.html)
