# Programación Orientada a Objetos en PHP

La Programación Orientada a Objetos (POO) constituye un paradigma fundamental en el desarrollo de software,
caracterizado por la estructuración del código en torno a "objetos" que encapsulan datos y comportamientos. En el
contexto de PHP, este enfoque facilita la creación de aplicaciones robustas, escalables y de fácil mantenimiento. El
presente documento ofrece una exposición detallada y sistemática de los principios esenciales de la POO en PHP, dirigida
a estudiantes universitarios y profesionales que buscan una comprensión rigurosa de este paradigma.

---

## 1. Fundamentos de la Programación Orientada a Objetos

### 1.1 Clases y Objetos

Una **clase** se define como una estructura abstracta que establece las propiedades y los comportamientos de un conjunto
de objetos. En PHP, las clases se declaran mediante la palabra clave `class`, seguida de un identificador y un bloque
que contiene atributos (propiedades) y funciones (métodos). Por su parte, un **objeto** representa una instancia
concreta de una clase, materializando las características definidas en esta última.

#### Ejemplo

```php
class Persona {
    public $nombre;
    public $edad;

    public function presentarse() {
        echo "Hola, mi nombre es {$this->nombre} y tengo {$this->edad} años.";
    }
}

$persona = new Persona();
$persona->nombre = "Juan";
$persona->edad = 25;
$persona->presentarse();  // Resultado: Hola, mi nombre es Juan y tengo 25 años.
```

En este código, `Persona` actúa como una plantilla que define las propiedades `$nombre` y `$edad`, así como el método
`presentarse()`. El objeto `$persona` es una instancia específica que asigna valores concretos a dichas propiedades.

### 1.2 Propiedades y Métodos

Las **propiedades** son variables asociadas a una clase que representan el estado de sus objetos, mientras que los
**métodos** son funciones que especifican las operaciones que estos pueden realizar. El acceso a las propiedades dentro
de los métodos se efectúa mediante `$this`, una referencia al objeto en curso.

#### Ejemplo

```php
class Coche {
    public $marca;
    public $modelo;

    public function mostrarInformacion() {
        echo "Este coche es un {$this->marca} {$this->modelo}.";
    }
}

$coche = new Coche();
$coche->marca = "Toyota";
$coche->modelo = "Corolla";
$coche->mostrarInformacion();  // Resultado: Este coche es un Toyota Corolla.
```

Aquí, `$marca` y `$modelo` son propiedades que almacenan el estado del objeto, y `mostrarInformacion()` es un método que
las utiliza para producir una salida.

---

## 2. Principio de Encapsulamiento

El **encapsulamiento** es un pilar de la POO que busca proteger los datos internos de un objeto mediante el control de
su acceso. En PHP, esto se implementa con los modificadores de visibilidad: `public`, `protected` y `private`. Los
métodos públicos, denominados **getters** y **setters**, se emplean para interactuar con propiedades restringidas.

#### Ejemplo

```php
class CuentaBancaria {
    private $saldo;

    public function __construct($saldoInicial) {
        $this->saldo = $saldoInicial;
    }

    public function getSaldo() {
        return $this->saldo;
    }

    public function depositar($monto) {
        if ($monto > 0) {
            $this->saldo += $monto;
            echo "Depositado $monto. Nuevo saldo: {$this->saldo}.";
        } else {
            echo "El monto debe ser positivo.";
        }
    }
}

$cuenta = new CuentaBancaria(1000);
echo $cuenta->getSaldo();  // Resultado: 1000
$cuenta->depositar(500);   // Resultado: Depositado 500. Nuevo saldo: 1500.
```

En este caso, `$saldo` es una propiedad privada, accesible únicamente a través de los métodos `getSaldo()` y
`depositar()`.

---

## 3. Herencia como Mecanismo de Reutilización

La **herencia** permite que una clase derivada (subclase) adopte las características de una clase base (superclase),
favoreciendo la reutilización de código. En PHP, se utiliza la palabra clave `extends`, y los métodos heredados pueden
ser sobrescritos o invocados mediante `parent::`.

#### Ejemplo

```php
class Vehiculo {
    public $marca;

    public function __construct($marca) {
        $this->marca = $marca;
    }

    public function mover() {
        echo "El vehículo {$this->marca} se está moviendo.";
    }
}

class Coche extends Vehiculo {
    public $puertas;

    public function __construct($marca, $puertas) {
        parent::__construct($marca);
        $this->puertas = $puertas;
    }

    public function mover() {
        echo "El coche {$this->marca} con {$this->puertas} puertas se mueve rápido.";
    }
}

$coche = new Coche("Ford", 4);
$coche->mover();  // Resultado: El coche Ford con 4 puertas se mueve rápido.
```

La clase `Coche` hereda de `Vehiculo`, extendiendo su funcionalidad con la propiedad `$puertas` y redefiniendo el método
`mover()`.

---

## 4. Polimorfismo y Abstracción

El **polimorfismo** permite tratar objetos de distintas clases de manera uniforme si comparten una interfaz o clase
abstracta común. En PHP, esto se logra mediante **interfaces** y **clases abstractas**.

#### Ejemplo con Interfaces

```php
interface Volador {
    public function volar();
}

class Pajaro implements Volador {
    public function volar() {
        echo "El pájaro bate las alas y vuela.";
    }
}

class Avion implements Volador {
    public function volar() {
        echo "El avión despega con motores.";
    }
}

$voladores = [new Pajaro(), new Avion()];
foreach ($voladores as $volador) {
    $volador->volar();
}
```

#### Ejemplo con Clases Abstractas

```php
abstract class Animal {
    public $nombre;

    public function __construct($nombre) {
        $this->nombre = $nombre;
    }

    abstract public function hacerSonido();
}

class Perro extends Animal {
    public function hacerSonido() {
        echo "{$this->nombre} dice: ¡Guau!";
    }
}

$perro = new Perro("Max");
$perro->hacerSonido();  // Resultado: Max dice: ¡Guau!
```
