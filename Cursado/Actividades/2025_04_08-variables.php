<?php
// Declaraciones de variables y tipos 
$int = 10;
$float = 0.05;
$string = "cadena texto";
$bool = true;

var_dump($int, $float, $string, $bool);

// Recibir numero mediante variable y mostrar el doble
function getDouble($num) {
	return $num * 2;
};

$number = (int)readline("Ingresa un numero:\n ");


print_r(getDouble($number) . "\n");
?>
