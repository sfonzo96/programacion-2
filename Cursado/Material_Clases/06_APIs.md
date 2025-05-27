## Fundamentos de APIs HTTP

### 1.1 Definición de una API
Una **Interfaz de Programación de Aplicaciones (API)** es un conjunto de reglas, protocolos y herramientas que permite la comunicación entre diferentes aplicaciones de software. Actúa como un intermediario que facilita el intercambio de datos o funcionalidades entre sistemas heterogéneos. Por ejemplo, una aplicación móvil puede usar una API para obtener datos meteorológicos de un servicio externo o para autenticar usuarios a través de plataformas como Google o Facebook. Las APIs son esenciales en la arquitectura moderna de software, ya que habilitan la interoperabilidad, modularidad y escalabilidad.

### 1.2 APIs HTTP
Una **API HTTP** utiliza el protocolo **Hypertext Transfer Protocol (HTTP)**, el mismo que sustenta la web, para permitir la comunicación cliente-servidor. Los clientes (como navegadores, aplicaciones móviles o scripts) envían solicitudes HTTP al servidor, que responde con datos en formatos estandarizados, como **JSON** (JavaScript Object Notation) o **XML** (Extensible Markup Language). HTTP es un protocolo sin estado, lo que significa que cada solicitud es independiente y debe contener toda la información necesaria para procesarse.

Las APIs HTTP son populares debido a la ubicuidad de HTTP, su compatibilidad con múltiples plataformas y su capacidad para integrarse con tecnologías web existentes. JSON es el formato predominante para las respuestas debido a su simplicidad, legibilidad y compatibilidad con lenguajes de programación modernos.

### 1.3 Arquitectura REST
**REST** (Representational State Transfer) es un estilo arquitectónico para diseñar sistemas distribuidos, propuesto por Roy Fielding en su disertación de 2000. REST organiza la interacción entre sistemas a través de los siguientes principios:

1. **Recursos**: Cada entidad (como un usuario, un producto o un pedido) se representa como un recurso, identificado por un **URI** único (por ejemplo, `/api/v1/usuarios/123`).
2. **Métodos HTTP**: Las operaciones sobre los recursos se realizan mediante métodos HTTP estándar:
   - **GET**: Recupera un recurso o una lista de recursos.
   - **POST**: Crea un nuevo recurso.
   - **PUT**: Actualiza un recurso existente (reemplazo completo).
   - **PATCH**: Actualiza parcialmente un recurso.
   - **DELETE**: Elimina un recurso.
3. **Sin estado**: Cada solicitud HTTP debe contener toda la información necesaria, sin depender de sesiones previas.
4. **Arquitectura cliente-servidor**: Separa las responsabilidades entre el cliente (interfaz de usuario) y el servidor (lógica y datos).
5. **Interfaz uniforme**: Define un conjunto estandarizado de convenciones para interactuar con recursos.
6. **Capas**: Permite la inclusión de intermediarios (como balanceadores de carga) sin afectar la interacción cliente-servidor.

REST es ampliamente adoptado por su simplicidad, escalabilidad y compatibilidad con la infraestructura web existente.

### 1.4 Códigos de Estado HTTP
Los códigos de estado HTTP indican el resultado de una solicitud. Se dividen en cinco categorías:

| Categoría | Rango | Descripción | Ejemplos |
|-----------|-------|-------------|----------|
| 1xx       | Informativos | Indican que la solicitud está en proceso | 100 Continue |
| 2xx       | Éxito | La solicitud se procesó correctamente | 200 OK, 201 Created, 204 No Content |
| 3xx       | Redirección | El cliente debe realizar una acción adicional | 301 Moved Permanently, 302 Found |
| 4xx       | Error del cliente | La solicitud es inválida o no puede procesarse | 400 Bad Request, 401 Unauthorized, 404 Not Found |
| 5xx       | Error del servidor | El servidor falló al procesar la solicitud | 500 Internal Server Error, 503 Service Unavailable |

Seleccionar el código de estado correcto sirve para comunicar el resultado de una solicitud de manera clara y predecible.

### 1.5 Mejores Prácticas para el Diseño de APIs RESTful
Para garantizar que una API sea robusta, usable y escalable, se deben seguir las siguientes prácticas:
1. **Nomenclatura clara**: Usar nombres de recursos en plural (por ejemplo, `/usuarios` en lugar de `/usuario`) y evitar verbos en los URIs (por ejemplo, `/getUsers`).
2. **Uso correcto de métodos HTTP**: Mapear operaciones CRUD a los métodos apropiados.
3. **Versionado**: Incluir la versión de la API en el URI (por ejemplo, `/api/v1/usuarios`) para facilitar actualizaciones sin romper clientes existentes.
4. **Autenticación y autorización**: Implementar mecanismos como claves API, OAuth 2.0 o JWT (JSON Web Tokens).
5. **Paginación y filtrado**: Para listas grandes, incluir parámetros como `?page=2` o `?limit=10`.
6. **Documentación clara**: Proporcionar documentación detallada, como OpenAPI/Swagger, para describir endpoints, parámetros y respuestas.
7. **Manejo de errores**: Devolver mensajes de error descriptivos en JSON con códigos de estado apropiados.
8. **CORS**: Configurar correctamente el Control de Acceso de Recursos de Origen Cruzado para permitir solicitudes desde dominios autorizados.

### 1.6 Estructura de una Respuesta API
Una respuesta API típica incluye:
- **Código de estado HTTP**: Indica el resultado de la solicitud.
- **Cuerpo de la respuesta**: Contiene los datos solicitados (en JSON, por ejemplo).
- **Encabezados**: Proporcionan metadatos, como `Content-Type: application/json`.

Ejemplo de respuesta para `GET /api/v1/usuarios/123`:
```json
{
  "id": 123,
  "nombre": "Juan Pérez",
  "email": "juan@example.com"
}
```

### 1.7 Seguridad en APIs HTTP
La seguridad es un aspecto crítico en el diseño de APIs. Algunas estrategias comunes incluyen:
- **Autenticación**: Verificar la identidad del cliente mediante claves API, OAuth o JWT.
- **Autorización**: Controlar el acceso a recursos mediante roles o permisos.
- **Cifrado**: Usar HTTPS para proteger los datos en tránsito.
- **Validación de entrada**: Sanitizar y validar los datos recibidos para prevenir inyecciones SQL u otros ataques.
- **Límite de solicitudes**: Implementar límites de tasa (rate limiting) para evitar abusos.
