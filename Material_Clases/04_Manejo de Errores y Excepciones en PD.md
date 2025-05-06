## Manejo de Errores y Excepciones en PDO  
PDO permite configurar el modo de errores a excepción (`ERRMODE_EXCEPTION`) para un manejo robusto. Con este modo (predeterminado en PHP 8+), cualquier error en la consulta lanzará una **PDOException**. Modo Exception lanzará una PDOException y señalará rápidamente el lugar del error. Esto facilita la depuración y hace que el script termine ante errores de forma controlada, revirtiendo transacciones pendientes si fuera necesario. 

Por ejemplo, conviene envolver las operaciones críticas en bloques `try-catch`:  
```php
try {
    $pdo->beginTransaction();
    // ... ejecutar consultas ...
    $pdo->commit();
} catch (PDOException $e) {
    $pdo->rollBack();  // Deshace cambios parciales
    error_log("Error en operación PDO: " . $e->getMessage());
    // Opcionalmente notificar al usuario de forma genérica
}
```  
La documentación advierte que *las transacciones se revierten automáticamente* si la excepción termina el script ([PHP: Errores y su manejo - Manual ](https://www.php.net/manual/es/pdo.error-handling.php)), pero en código robusto se recomienda capturar excepciones y llamar explícitamente a `rollBack()`. Además, en el bloque `catch` se debe guardar el mensaje de error en un log o archivo, sin exponer detalles internos al usuario (por seguridad) ([Developer Quickstart: PHP Data Objects and MariaDB | MariaDB](https://mariadb.com/resources/blog/developer-quickstart-php-data-objects-and-mariadb/)). Un mensaje genérico como *“Error en la base de datos”* evita filtrar información sensible. 

Finalmente, tenga en cuenta que **PDO::construct** siempre lanzará una excepción si falla la conexión, independientemente del modo de error ([PHP: Errores y su manejo - Manual ](https://www.php.net/manual/es/pdo.error-handling.php)). Por ello, es imprescindible usar `try/catch` al crear la instancia de PDO y manejar `PDOException` desde el inicio. 

## Buenas Prácticas con PDO y MariaDB  
- **Evitar mezclar lógica:** Separar la lógica de acceso a datos de la lógica de presentación o negocio. Se sugiere mantener la configuración de conexión (credenciales, DSN, opciones) en un archivo independiente o clase DAO. Se recomienda almacenar credenciales fuera del directorio público, por ejemplo en un archivo de configuración o en variables de entorno.  
- **Transacciones cuando corresponda:** Para operaciones que involucran múltiples consultas (por ejemplo, transferencia de fondos entre cuentas), usar transacciones con `beginTransaction()`, `commit()` y `rollBack()`. Esto garantiza atomicidad (ACID) y permite deshacer cambios ante errores.  
- **Preparar TODAS las consultas con datos externos:** Incluso si un valor proviene de un formulario o variable, siempre usar marcadores. Nunca concatenar directamente variables en la cadena SQL. Esto cierra la puerta a inyecciones.  
- **Configurar atributos recomendados:** Además del modo excepción, conviene establecer `PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC` para obtener resultados en arrays asociativos, y `PDO::ATTR_EMULATE_PREPARES => false` para usar sentencias preparadas reales. Así se maximiza la compatibilidad y seguridad.  
- **Manejo de errores consistente:** Incluir cada llamada a base de datos dentro de bloques `try/catch`. Como regla general, “toda acción con PDO debería estar en un `try` para capturar excepciones”.  
- **Validar y sanear datos:** Antes de enlazar parámetros, validar formato (por ejemplo, formato de email) y sanear si es necesario. Aunque PDO protege de inyección, la validación asegura que el dato cumple con las expectativas.  
- **Respetar ACID en consultas:** No ejecutar sentencias DDL (p.ej. `CREATE TABLE`) en medio de una transacción si el motor lo soporta, para evitar commits implícitos inesperados.  
