<?php
// Operaciones aritmeticas basicas y mostrar resultado
function sumar($num1, $num2) {
	return $num1 + $num2;
}

function restar($num1, $num2) {
	return $num1 - $num2;
}

function multiplicar($num1, $num2) {
	return $num1 * $num2;
}

function dividir($num1, $num2) {
	if ($num2 == 0) {
		echo "Second number has to be different than 0 for division to work. Second number will be 1";

		return $num1;
	}
	

	return $num1 / $num2;
}

$num1 = readline("Introduce first number for arithmetics: \n");
$num2 = readline("Introduce second number for arithmetics: \n");

if (! is_numeric($num1) || ! is_numeric($num2)){
	echo "Both numbers must be numeric! Script goes brrr\n";
	exit;
}


$suma = sumar($num1, $num2);
$resta = restar($num1, $num2);
$multiplicacion = multiplicar($num1, $num2);
$division = dividir($num1, $num2);

print_r("Results are:\n -Addition: {$suma}\n -Subtraction: {$resta}\n -Multiplication: {$multiplicacion}\n -Division: {$division}\n\n");


// Comparar dos numeros y mostrar cual es mayor o si son iguales
$numCmp1 = readline("Introduce first number for comparison: \n");
$numCmp2 = readline("Introduce second number for comparison: \n");

if ($numCmp1 == $numCmp2) {
	echo "Both comparison numbers are the same\n";
} else if ($numCmp1 > $numCmp2) {
	echo "{$numCmp1} is bigger than {$numCmp2}\n";
} else {
	echo "{$numCmp1} is smaller than {$numCmp2}\n";
}
echo "\n";

// Concatenar dos cadenas de texto
$string1 = readline("Introduce the first string: \n");
$string2 = readline("Introduce the second string: \n");
$concatenated = "{$string1} {$string2}";
echo "Concatenated string: {$concatenated}\n";

?>
