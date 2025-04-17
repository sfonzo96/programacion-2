<?php
$names = ['Santiago', 'Maximo', 'Sebastian', 'Alejandro', 'Maximiliano'];
?>

<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="UTD-8">
		<title>Listado Nombres</title>
	</head>
	<body>
		<h1>Listado de nombres</h1>
		<ul>
			<?php
			foreach ($names as $name) {
				echo "<li> {$name} </li>";
			}
			?>
		</ul>
	</body>
</html>

