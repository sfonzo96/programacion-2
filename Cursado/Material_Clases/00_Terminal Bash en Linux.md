# Terminal Bash en Linux

La terminal de Linux es una herramienta para la administración del sistema y el desarrollo. Bash (Bourne Again Shell) es el intérprete de comandos predeterminado en muchas distribuciones de Linux.

### Navegación en el Sistema de Archivos

Para moverte entre directorios y conocer tu ubicación actual:

```bash
pwd  # Muestra el directorio actual
ls   # Lista los archivos y carpetas en el directorio actual
cd <directorio>  # Cambia de directorio
cd ..  # Sube un nivel en la jerarquía de directorios
cd ~  # Vuelve al directorio home del usuario
```

### Manipulación de Archivos y Directorios

Para crear, copiar, mover y eliminar archivos o carpetas:

```bash
touch archivo.txt  # Crea un archivo vacío
mkdir nuevo_directorio  # Crea un directorio
cp archivo.txt copia_archivo.txt  # Copia un archivo
mv archivo.txt nuevo_directorio/  # Mueve un archivo a un directorio
rm archivo.txt  # Elimina un archivo
rm -r directorio/  # Elimina un directorio y su contenido
```

### Visualización y Edición de Archivos

Para leer y modificar archivos desde la terminal:

```bash
cat archivo.txt  # Muestra el contenido de un archivo
less archivo.txt  # Muestra el contenido página por página
nano archivo.txt  # Editor de texto en terminal
vim archivo.txt  # Editor avanzado (requiere aprendizaje previo)
```

### Gestión de Procesos y Tareas

Para listar, detener y ejecutar procesos:

```bash
ps aux  # Lista todos los procesos en ejecución
kill <PID>  # Termina un proceso por su ID (PID)
top  # Muestra los procesos en tiempo real
htop  # Alternativa mejorada de top (requiere instalación)
```

### Permisos y Propiedades de Archivos

Para gestionar permisos y propietarios de archivos:

```bash
ls -l  # Muestra los permisos y propietarios de archivos
chmod 755 archivo.sh  # Cambia los permisos de un archivo
chown usuario:grupo archivo.txt  # Cambia el propietario del archivo
```

### Redirección y Piping

Para manipular la salida de comandos:

```bash
comando > archivo.txt  # Guarda la salida en un archivo
comando >> archivo.txt  # Agrega la salida al final de un archivo
comando1 | comando2  # Pasa la salida de un comando como entrada de otro
```

### Comandos de Red y Conectividad

Para administrar conexiones y redes:

```bash
ping google.com  # Comprueba la conectividad a Internet
wget http://example.com/archivo.txt  # Descarga un archivo
curl http://example.com  # Obtiene el contenido de una URL
```

### Administración de Usuarios y Grupos

Para gestionar usuarios en el sistema:

```bash
whoami  # Muestra el usuario actual
id  # Muestra detalles del usuario y su grupo
sudo adduser usuario  # Crea un nuevo usuario
sudo userdel usuario  # Elimina un usuario
```

### Compresión y Descompresión

Para trabajar con archivos comprimidos:

```bash
tar -cvf archivo.tar directorio/  # Crea un archivo tar
tar -xvf archivo.tar  # Extrae un archivo tar
gzip archivo.txt  # Comprime un archivo en formato gzip
gunzip archivo.txt.gz  # Descomprime un archivo gzip
```

### Automatización con Scripts Bash

Para crear y ejecutar scripts:

```bash
echo "#!/bin/bash" > script.sh  # Crea un script
chmod +x script.sh  # Otorga permisos de ejecución
./script.sh  # Ejecuta el script
```

### Apagado y Reinicio con `init`

Para gestionar el apagado y reinicio del sistema:

```bash
sudo init 0  # Apaga el sistema
sudo init 6  # Reinicia el sistema
```

El comando `init` se utiliza para cambiar el nivel de ejecución del sistema, y `init 0` y `init 6` son comandos comunes para apagar y reiniciar respectivamente.
