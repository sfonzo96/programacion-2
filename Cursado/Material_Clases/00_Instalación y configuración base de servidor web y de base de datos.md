
## Implementación Avanzada de Apache2 y MariaDB en Debian con VirtualHost

### Preparación del Entorno

Antes de iniciar con la implementación, es importante actualizar los paquetes del sistema para mitigar posibles vulnerabilidades y garantizar compatibilidad con las versiones más recientes:

```bash
sudo apt update && sudo apt upgrade -y
```

### Instalación de Apache2

Apache2 es un servidor HTTP ampliamente adoptado por su flexibilidad y rendimiento. Lo vamos a instalar con el siguiente comando:

```bash
sudo apt install apache2 -y
```

Para validar que el servicio está activo, se puede acceder a la dirección IP del servidor desde un navegador.

### Instalación y Configuración de MariaDB

MariaDB, un sistema de gestión de bases de datos relacionales, se instala con:

```bash
sudo apt install mariadb-server -y
```

Para realizar la configuración de seguridad inicial, vamos a ejecutar:

```bash
sudo mysql_secure_installation
```

Este script permite definir una contraseña para el usuario root, eliminar accesos anónimos, restringir el acceso remoto y eliminar la base de datos de prueba.

### Configuración del Entorno Web

Se establece un directorio específico para el dominio y se asignan permisos:

```bash
sudo mkdir -p /var/www/midominio.local/public_html
sudo chown -R $USER:$USER /var/www/midominio.local/public_html
sudo chmod -R 755 /var/www/midominio.local
```

Para validar la configuración, se genera un archivo de prueba:

```bash
echo "<html><head><title>Mi Dominio</title></head><body><h1>Sitio funcionando</h1></body></html>" > /var/www/midominio.local/public_html/index.html
```

### Configuración de VirtualHost en Apache

Se crea un archivo de configuración para definir el VirtualHost:

```bash
sudo nano /etc/apache2/sites-available/midominio.local.conf
```

Se debe incluir la siguiente configuración:

```apache
<VirtualHost *:80>
    ServerAdmin webmaster@midominio.local
    ServerName midominio.local
    ServerAlias www.midominio.local
    DocumentRoot /var/www/midominio.local/public_html
    ErrorLog ${APACHE_LOG_DIR}/midominio_error.log
    CustomLog ${APACHE_LOG_DIR}/midominio_access.log combined

    <Directory /var/www/midominio.local/public_html>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

Para activar la configuración, se habilita el VirtualHost:

```bash
sudo a2ensite midominio.local.conf
```

Si se necesita deshabilitar el sitio predeterminado:

```bash
sudo a2dissite 000-default.conf
```

### Validación y Aplicación de la Configuración

Antes de reiniciar Apache, se recomienda validar la sintaxis de la configuración:

```bash
sudo apache2ctl configtest
```

Si el resultado es "Syntax OK", se procede a recargar el servicio para aplicar los cambios:

```bash
sudo systemctl reload apache2
```

### Configuración Local del Archivo Hosts (Opcional)

Para entornos de desarrollo local, es posible agregar la siguiente entrada en `/etc/hosts` para resolver el dominio:

```bash
127.0.0.1    midominio.local
```
