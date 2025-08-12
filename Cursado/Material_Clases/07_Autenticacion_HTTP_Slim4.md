## Introducción a la Autenticación en Aplicaciones Web

La autenticación es el proceso de verificar la identidad de un usuario o sistema. Este proceso asegura que las acciones realizadas en una aplicación, como acceder a datos personales o realizar transacciones, se realicen de forma segura. Tres métodos de autenticación comunes en HTTP, implementados en PHP usando el framework Slim4 son: autenticación básica, basada en sesiones y basada en tokens (JWT). Cada método presenta sus propias características, ventajas y desafíos.

### Conceptos Básicos

* **Credenciales**: Información como nombres de usuario y contraseñas que identifican a un usuario.
* **Sesiones**: Mecanismo para mantener el estado del usuario entre múltiples solicitudes HTTP.
* **Tokens**: Cadenas que representan la identidad del usuario, usadas en autenticación sin estado.
* **Middleware**: Componentes en Slim4 que interceptan solicitudes HTTP para procesarlas antes de llegar a las rutas.

---

## Métodos de Autenticación en HTTP

### 1. Autenticación Básica

La autenticación básica es un esquema integrado en el protocolo HTTP. El cliente envía un nombre de usuario y una contraseña en el encabezado `Authorization`, codificados en Base64 (por ejemplo, `Authorization: Basic dXNlcjpwYXNzd29yZA==`). Es importante usar HTTPS para cifrar la comunicación, ya que la codificación Base64 no es segura por sí misma.

| **Aspecto**      | **Detalles**                                                                     |
| ---------------- | -------------------------------------------------------------------------------- |
| **Mecanismo**    | Credenciales enviadas en el encabezado `Authorization` codificado en Base64.     |
| **Seguridad**    | Requiere HTTPS para proteger las credenciales.                                   |
| **Casos de Uso** | APIs simples, servicios internos con seguridad gestionada a nivel de red.        |
| **Ventajas**     | Fácil de implementar, soportado nativamente por HTTP.                            |
| **Desventajas**  | Credenciales enviadas en cada solicitud, no escalable para aplicaciones grandes. |

#### Implementación en Slim4

Se utiliza el middleware `tuupola/slim-basic-auth`. Pasos:

1. **Instalar el middleware**:

```bash
composer require tuupola/slim-basic-auth
```

2. **Configurar el middleware**:

```php
<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Tuupola\Middleware\HttpBasicAuthentication;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->add(new HttpBasicAuthentication([
    "path" => "/api",
    "users" => [
        "user" => "password"
    ]
]));

$app->get('/api/protected', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Ruta protegida accesible");
    return $response;
});

$app->run();
```

3. **Probar la autenticación**:
   Utilizar Postman o cURL con el header `Authorization: Basic dXNlcjpwYXNzd29yZA==`.

---

### 2. Autenticación Basada en Sesiones

Este enfoque usa cookies para mantener el estado del usuario.

| **Aspecto**      | **Detalles**                                                                           |
| ---------------- | -------------------------------------------------------------------------------------- |
| **Mecanismo**    | Usa cookies para enviar un ID de sesión que identifica al usuario en el servidor.      |
| **Seguridad**    | Requiere protección contra fijación de sesiones, CSRF y manejo adecuado de expiración. |
| **Casos de Uso** | Aplicaciones web con interfaces de usuario, como sitios con paneles de control.        |
| **Ventajas**     | Familiar para aplicaciones web tradicionales, fácil de implementar.                    |
| **Desventajas**  | Requiere almacenamiento en el servidor, menos escalable para APIs.                     |

#### Implementación en Slim4

1. **Instalar el middleware**:

```bash
composer require bryanjhv/slim-session
```

2. **Configurar middleware y rutas**:

```php
<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Middleware\Session;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->add(new Session([
    'name' => 'my_session',
    'autorefresh' => true,
    'lifetime' => '1 hour',
]));

// Ruta de login
$app->post('/login', function (Request $request, Response $response) {
    $data = $request->getParsedBody();
    $username = $data['username'] ?? '';
    $password = $data['password'] ?? '';

    if ($username === 'user' && $password === 'password') {
        $_SESSION['user'] = $username;
        $response->getBody()->write("Inicio de sesión exitoso");
    } else {
        $response->getBody()->write("Credenciales inválidas");
        return $response->withStatus(401);
    }
    return $response;
});

// Middleware de autenticación
$authMiddleware = function (Request $request, RequestHandler $handler) {
    if (!isset($_SESSION['user'])) {
        $response = new \Slim\Psr7\Response();
        $response->getBody()->write("No autorizado");
        return $response->withStatus(401);
    }
    return $handler->handle($request);
};

// Ruta protegida
$app->get('/protected', function (Request $request, Response $response) {
    $response->getBody()->write("Ruta protegida: Bienvenido, " . $_SESSION['user']);
    return $response;
})->add($authMiddleware);

$app->run();
```

3. **Probar**:
   POST a `/login` con `{ "username": "user", "password": "password" }`. Luego acceder a `/protected` con la cookie de sesión.

---

### 3. Autenticación Basada en Tokens (JWT)

La autenticación basada en tokens, como JSON Web Tokens (JWT), es sin estado, lo que significa que el servidor no almacena información de la sesión. Un JWT consta de tres partes: encabezado, carga útil y firma, codificados en Base64 y separados por puntos (`.`). El cliente envía el token en el encabezado `Authorization: Bearer <token>`. 

| **Aspecto**      | **Detalles**                                                                  |
| ---------------- | ----------------------------------------------------------------------------- |
| **Mecanismo**    | Token JWT enviado en el encabezado `Authorization`, validado por el servidor. |
| **Seguridad**    | Requiere HTTPS, validación de firma y manejo de expiración.                   |
| **Casos de Uso** | APIs, aplicaciones de página única (SPAs), aplicaciones móviles.              |
| **Ventajas**     | Sin estado, escalable, no requiere almacenamiento en el servidor.             |
| **Desventajas**  | Complejidad en la gestión de tokens (por ejemplo, renovación, revocación).    |

#### Implementación en Slim4

1. **Instalar las dependencias**:

```bash
composer require tuupola/slim-jwt-auth firebase/php-jwt
```

2. **Código de configuración**:

```php
<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Tuupola\Middleware\JwtAuthentication;
use Firebase\JWT\JWT;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

// Ruta de login para emitir token
$app->post('/login', function (Request $request, Response $response) {
    $data = $request->getParsedBody();
    $username = $data['username'] ?? '';
    $password = $data['password'] ?? '';

    if ($username === 'user' && $password === 'password') {
        $key = "your_secret_key";
        $payload = [
            "iss" => "example.com",
            "aud" => "example.com",
            "iat" => time(),
            "nbf" => time(),
            "exp" => time() + 3600,
            "data" => [
                "username" => $username
            ]
        ];
        $token = JWT::encode($payload, $key, 'HS256');
        $response->getBody()->write(json_encode(["token" => $token]));
    } else {
        $response->getBody()->write("Credenciales inválidas");
        return $response->withStatus(401);
    }
    return $response->withHeader('Content-Type', 'application/json');
});

// Middleware JWT
$app->add(new JwtAuthentication([
    "secret" => "your_secret_key",
    "attribute" => "token",
    "path" => "/api",
    "ignore" => ["/login"],
    "algorithm" => ["HS256"]
]));

// Ruta protegida
$app->get('/api/protected', function (Request $request, Response $response) {
    $token = $request->getAttribute('token');
    $username = $token['data']['username'];
    $response->getBody()->write("Hola, $username");
    return $response;
});

$app->run();
```

3. **Probar**:
   POST a `/login` con `{ "username": "user", "password": "password" }`, luego usar `Authorization: Bearer <token>` para acceder a `/api/protected`.

---

## Comparación y Mejores Prácticas

| **Método**             | **Ventajas**                                | **Desventajas**                          | **Casos de Uso**                  |
| ---------------------- | ------------------------------------------- | ---------------------------------------- | --------------------------------- |
| **Básica**             | Simple, soportada nativamente por HTTP.     | Credenciales enviadas en cada solicitud. | APIs internas, servicios simples. |
| **Basada en Sesiones** | Familiar, ideal para interfaces de usuario. | Requiere almacenamiento en el servidor.  | Aplicaciones web tradicionales.   |
| **JWT**                | Sin estado, escalable, ideal para APIs.     | Complejidad en gestión de tokens.        | APIs, SPAs, aplicaciones móviles. |