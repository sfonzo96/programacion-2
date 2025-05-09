Guardar todas las resoluciones en una carpeta llamada "POO en PHP", en subcarpetas por tema numeradas al inicio (por
ejemplo, "01 - Fundamentos de la Programación Orientada a Objetos"). Cada ejercicio debe estar en un archivo nombrado
Ejercicio_Nro.php (como Ejercicio_01.php).

## 1: Fundamentos de la Programación Orientada a Objetos

**Ejercicio 1**  
 Crea una clase llamada `Libro` con las propiedades `titulo` y `autor`. Instancia un objeto de esta clase, asigna valores
a sus propiedades y muestra el título y el autor en pantalla.

**Ejercicio 2**  
 Crea una clase `Rectangulo` con las propiedades `largo` y `ancho`. Agrega un método `calcularArea` que retorne el área del
rectángulo (`largo * ancho`). Crea un objeto, asigna valores y muestra el resultado del área.

**Ejercicio 3**  
 Define una clase `Estudiante` con las propiedades `nombre`, `edad` y `matricula`. Añade un método `mostrarDatos` que imprima
los datos del estudiante. Instancia un objeto y usa el método.

**Ejercicio 4**  
 Crea una clase `Coche` con las propiedades `marca`, `modelo` y `color`. Agrega un método `detalles` que muestre un mensaje
con la información del coche. Crea un objeto y ejecuta el método.

**Ejercicio 5**  
 Define una clase `Persona` con las propiedades `nombre` y `edad`. Incluye un método `esAdulto` que retorne `true` si la
edad es mayor o igual a 18, y `false` en caso contrario. Prueba el método con un objeto.

**Ejercicio 6**  
 Crea una clase `Cuenta` con la propiedad `saldo`. Agrega métodos `ingresar` (suma un monto al saldo) y `retirar` (resta
un monto del saldo). Instancia un objeto, realiza operaciones y muestra el saldo final.

**Ejercicio 7**  
 Define una clase `Producto` con las propiedades `nombre`, `precio` y `stock`. Añade un método `valorInventario` que calcule
el valor total (`precio * stock`). Crea un objeto y muestra el resultado.

**Ejercicio 8**  
 Crea una clase `Circulo` con la propiedad `radio`. Incluye un método `calcularPerimetro` que retorne el perímetro (`2 * π * radio`).
Instancia un objeto y muestra el perímetro.

**Ejercicio 9**  
 Define una clase `Trabajador` con las propiedades `nombre`, `cargo` y `salario`. Agrega un método `informacion` que imprima
los datos del trabajador. Crea un objeto y usa el método.

**Ejercicio 10**  
 Crea una clase `Triangulo` con las propiedades `base` y `altura`. Añade un método `area` que calcule el área (`base * altura / 2`).
Instancia un objeto y muestra el área.

## 2: Principio de Encapsulamiento

**Ejercicio 1**  
 Crea una clase `CuentaBancaria` con una propiedad privada `saldo`. Incluye un constructor para inicializar el saldo, un
método `getSaldo` para consultarlo y un método `depositar` que sume un monto positivo. Prueba con un objeto.

**Ejercicio 2**  
 Define una clase `Usuario` con una propiedad privada `edad`. Agrega un constructor, un método `getEdad` y un método `setEdad`
que solo acepte valores mayores a 0. Instancia un objeto y verifica su comportamiento.

**Ejercicio 3**  
 Crea una clase `Producto` con una propiedad privada `precio`. Incluye un constructor, un método `getPrecio` y un método
`setPrecio` que valide que el precio sea positivo. Prueba con valores válidos e inválidos.

**Ejercicio 4**  
 Define una clase `Vehiculo` con una propiedad privada `kilometros`. Agrega un constructor, un método `getKilometros` y un
método `avanzar` que incremente los kilómetros si el valor es positivo. Crea un objeto y muestra el resultado.

**Ejercicio 5**  
 Crea una clase `Estudiante` con una propiedad privada `calificaciones` (arreglo). Incluye un constructor, un método `getPromedio`
para calcular el promedio y un método `agregarCalificacion` que valide valores entre 0 y 10. Prueba el promedio.

**Ejercicio 6**  
 Define una clase `Rectangulo` con propiedades privadas `largo` y `ancho`. Agrega un constructor, métodos `getLargo` y `getAncho`,
y un método `setDimensiones` que valide valores positivos. Instancia y verifica.

**Ejercicio 7**  
 Crea una clase `Libro` con una propiedad privada `numeroPaginas`. Incluye un constructor, un método `getPaginas` y un método
`setPaginas` que solo acepte valores mayores a 0. Prueba con un objeto.

**Ejercicio 8**  
 Define una clase `Empleado` con una propiedad privada `sueldo`. Agrega un constructor, un método `getSueldo` y un método
`aumentarSueldo` que aplique un incremento porcentual. Instancia y muestra el nuevo sueldo.

**Ejercicio 9**  
 Crea una clase `Circulo` con una propiedad privada `radio`. Incluye un constructor, un método `getRadio` y un método `setRadio`
que valide valores positivos. Prueba con valores válidos e inválidos.

**Ejercicio 10**  
 Define una clase `CuentaCorriente` con propiedades privadas `saldo` y `limite`. Agrega un constructor, un método `getSaldo`
y un método `retirar` que permita retiros solo si el saldo más el límite lo cubren. Prueba con un objeto.

## 3: Herencia como Mecanismo de Reutilización

**Ejercicio 1**  
 Crea una clase base `Vehiculo` con la propiedad `marca` y un método `avanzar`. Define una subclase `Moto` que herede de
`Vehiculo` y añada la propiedad `cilindrada`. Sobrescribe `avanzar` con un mensaje diferente. Prueba con un objeto.

**Ejercicio 2**  
 Define una clase base `Animal` con la propiedad `especie` y un método `emitirSonido`. Crea una subclase `Gato` que sobrescriba
`emitirSonido` para mostrar "¡Miau!". Instancia y ejecuta el método.

**Ejercicio 3**  
 Crea una clase base `Persona` con las propiedades `nombre` y `edad`, y un método `saludar`. Define una subclase `Profesor`
que añada `materia` y sobrescriba `saludar` incluyendo la materia. Prueba con un objeto.

**Ejercicio 4**  
 Define una clase base `Figura` con un método abstracto `calcularArea`. Crea una subclase `Cuadrado` con la propiedad `lado`
e implementa `calcularArea`. Instancia y muestra el área.

**Ejercicio 5**  
 Crea una clase base `Producto` con `nombre` y `precio`, y un método `detalle`. Define una subclase `ProductoOferta` que
añada `descuento` y sobrescriba `detalle` con el descuento. Prueba con un objeto.

**Ejercicio 6**  
 Define una clase base `Cuenta` con `saldo` y métodos `depositar` y `retirar`. Crea una subclase `CuentaPremium` que añada
`bonificacion` y un método `aplicarBonificacion`. Instancia y muestra el saldo tras aplicar la bonificación.

**Ejercicio 7**  
 Crea una clase base `Instrumento` con un método abstracto `sonar`. Define una subclase `Piano` que implemente `sonar` con
un mensaje específico. Instancia y ejecuta el método.

**Ejercicio 8**  
 Define una clase base `Vehiculo` con `velocidad` y un método `acelerar`. Crea una subclase `Camion` que sobrescriba `acelerar`
para aumentar la velocidad en 10 unidades. Prueba con un objeto.

**Ejercicio 9**  
 Crea una clase base `Empleado` con `nombre` y `salario`, y un método `calcularPago`. Define una subclase `Freelancer` con
`horas` y sobrescriba `calcularPago` basado en horas. Instancia y muestra el pago.

**Ejercicio 10**  
 Define una clase base `Animal` con `nombre` y un método `moverse`. Crea una subclase `Pez` que añada `tipoAgua` y sobrescriba
`moverse` con un mensaje diferente. Prueba con un objeto.

## 4: Polimorfismo y Abstracción

**Ejercicio 1**  
 Crea una interfaz `Nadador` con un método `nadar`. Define dos clases, `Pez` y `Persona`, que implementen `nadar` con mensajes
distintos. Crea un arreglo de objetos y recorrelo ejecutando `nadar`.

**Ejercicio 2**  
 Define una clase abstracta `Vehiculo` con un método abstracto `desplazarse`. Crea subclases `Bicicleta` y `Avion` que implementen
`desplazarse`. Usa un arreglo para probar los métodos.

**Ejercicio 3**  
 Crea una interfaz `Printable` con un método `imprimir`. Define clases `Documento` y `Foto` que implementen `imprimir` de
forma diferente. Recorre un arreglo de objetos ejecutando el método.

**Ejercicio 4**  
 Define una clase abstracta `Figura` con un método abstracto `area`. Crea subclases `Triangulo` y `Circulo` que implementen
`area`. Usa un arreglo para mostrar las áreas.

**Ejercicio 5**  
 Crea una interfaz `Reproducible` con un método `reproducir`. Define clases `Pelicula` y `Podcast` que implementen `reproducir`.
Recorre un arreglo ejecutando el método.

**Ejercicio 6**  
 Define una clase abstracta `Trabajador` con un método abstracto `calcularIngreso`. Crea subclases `Fijo` y `Temporal` que
implementen `calcularIngreso` distinto. Muestra los ingresos en un arreglo.

**Ejercicio 7**  
 Crea una interfaz `Comunicable` con un método `enviarMensaje`. Define clases `Correo` y `Texto` que implementen `enviarMensaje`.
Recorre un arreglo ejecutando el método.

**Ejercicio 8**  
 Define una clase abstracta `Instrumento` con un método abstracto `tocar`. Crea subclases `Violin` y `Bateria` que implementen
`tocar`. Usa un arreglo para probar los métodos.

**Ejercicio 9**  
 Crea una interfaz `Calculable` con un método `calcularPerimetro`. Define clases `Cuadrado` y `Circulo` que implementen `calcularPerimetro`.
Recorre un arreglo mostrando los perímetros.

**Ejercicio 10**  
 Define una clase abstracta `Animal` con un método abstracto `alimentarse`. Crea subclases `Leon` y `Pajaro` que implementen
`alimentarse`. Usa un arreglo para ejecutar los métodos.
