<?php
// Verificacion de edad
$edad = readline("Introduce your age\n");
if ($edad > 18) {
	echo "Mayor de edad\n";
} else {
	echo "Menor de edad\n";
}

echo "\n";

// Bucle for que imprima los numeros del 1 al 20
for ($i = 1; $i <= 20; $i++) {
	echo "{$i}\n";
}

// Bucle while que sume numeros del 1 al 50 y muestre suma total
$i = 1;
$sum = 0;
while ($i <= 50) {
	$sum += $i++;
}
echo "{$sum}\n";

// Switch que en funcion de una variable de dia de la semana como numero imprima el nombre correspondiente
$days = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];
$dayIndex = (int) readline("Introduce a number representing a day of the week. For instance: 1 for Monday\n");

if ($dayIndex < 1) {
	echo "The number should be in between 1 and 7. Script goes brrr...\n";
	exit;
}

echo "{$days[$dayIndex - 1]}\n";
?>
