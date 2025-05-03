
## Directorios en la raíz del sistema de archivos de Linux (`/`)

En Linux, el sistema de archivos se organiza en una jerarquía con varios directorios en la raíz (`/`) del sistema. Cada uno tiene un propósito específico y contiene archivos esenciales del sistema. Aquí hay una descripción de los directorios más comunes que encontrarás en `/`:

### `/` - Raíz

El directorio raíz es el directorio principal en la jerarquía de archivos de Linux. Todos los demás directorios están contenidos dentro de este directorio.

### `/bin` - Archivos Binarios

Contiene los binarios esenciales del sistema, como programas y comandos necesarios para el sistema operativo, que son utilizados por el usuario y por el sistema, incluso en el modo de emergencia.

Ejemplo:
- `ls`
- `cp`
- `mv`

### `/boot` - Archivos de Arranque

Este directorio contiene los archivos necesarios para arrancar el sistema, como el kernel de Linux y otros archivos de arranque. No debe modificarse sin precaución.

Ejemplo:
- `vmlinuz` (kernel)
- `initrd.img` (imagen del initramfs)

### `/dev` - Archivos de Dispositivos

Contiene archivos especiales de dispositivos que representan dispositivos de hardware como discos duros, terminales y otros periféricos.

Ejemplo:
- `/dev/sda` (primer disco duro)
- `/dev/tty1` (primer terminal)

### `/etc` - Archivos de Configuración

Este directorio contiene archivos de configuración globales del sistema. Aquí se encuentran configuraciones para el sistema operativo, los servicios y los programas instalados.

Ejemplo:
- `/etc/passwd` (información de usuarios)
- `/etc/fstab` (configuración de sistemas de archivos)

### `/home` - Directorios de los Usuarios

Contiene los directorios personales de los usuarios, donde guardan sus archivos y configuraciones. Cada usuario tiene su propio directorio bajo `/home`.

Ejemplo:
- `/home/usuario` (directorio personal de un usuario)

### `/lib` - Librerías del Sistema

Contiene las bibliotecas compartidas esenciales que los binarios en `/bin` y `/sbin` necesitan para ejecutarse.

Ejemplo:
- `/lib/libc.so.6` (biblioteca estándar de C)

### `/media` - Puntos de Montaje de Medios Extraíbles

Este directorio es donde se montan los dispositivos de almacenamiento extraíbles, como CD-ROMs, DVD, unidades USB y otros dispositivos similares.

Ejemplo:
- `/media/cdrom`
- `/media/usb`

### `/mnt` - Puntos de Montaje Temporales

Utilizado históricamente para montar dispositivos de almacenamiento de manera temporal. Aunque hoy en día se utiliza menos, algunos sistemas todavía lo usan para montar sistemas de archivos temporales.

### `/opt` - Paquetes de Software Opcionales

Contiene software adicional que no forma parte de la distribución estándar de Linux. Se utiliza generalmente para instalar aplicaciones de terceros.

Ejemplo:
- `/opt/google/chrome` (directorio de Google Chrome)

### `/proc` - Sistema de Archivos Virtual

Contiene información sobre el sistema y los procesos en ejecución. Es un sistema de archivos virtual que no ocupa espacio en disco, pero proporciona información en tiempo real del sistema.

Ejemplo:
- `/proc/cpuinfo` (información sobre la CPU)
- `/proc/meminfo` (información sobre la memoria)

### `/root` - Directorio Home del Usuario Root

Es el directorio home del superusuario (root). Al igual que `/home`, pero dedicado exclusivamente a las configuraciones y archivos del administrador del sistema.

### `/run` - Datos del Sistema en Ejecución

Contiene archivos temporales y de información sobre el sistema que están en ejecución. Estos datos son necesarios para el funcionamiento del sistema y los servicios.

### `/sbin` - Binarios del Sistema

Contiene binarios esenciales para la administración del sistema, generalmente utilizados solo por el administrador (root). Los comandos aquí son necesarios para la configuración y mantenimiento del sistema.

Ejemplo:
- `fsck` (comando para verificar el sistema de archivos)
- `shutdown`

### `/srv` - Datos de Servicios

Contiene datos específicos de los servicios del sistema, como archivos web, bases de datos o cualquier otro servicio que el sistema esté proporcionando.

### `/sys` - Sistema de Archivos Virtual del Sistema

Este directorio contiene información sobre los dispositivos del núcleo (kernel) y la configuración del sistema. Al igual que `/proc`, es un sistema de archivos virtual.

### `/tmp` - Archivos Temporales

Contiene archivos temporales creados por aplicaciones y el sistema. Los archivos en `/tmp` se suelen borrar al reiniciar el sistema.

### `/usr` - Archivos de Usuario

Contiene la mayoría de los programas, librerías, documentación y archivos que no son esenciales para el arranque del sistema. Es común que el directorio `/usr` contenga muchos de los programas y herramientas que los usuarios pueden ejecutar.

Ejemplo:
- `/usr/bin` (programas ejecutables)
- `/usr/lib` (librerías compartidas)

### `/var` - Archivos Variables

Contiene archivos que son susceptibles a cambiar con el tiempo, como archivos de log, bases de datos y archivos de correo.

Ejemplo:
- `/var/log` (archivos de log)
- `/var/mail` (buzones de correo)

