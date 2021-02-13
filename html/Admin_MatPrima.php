<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"/>
		<title>SGSR - Actualizar Materias Primas</title>
		<link rel="stylesheet" type="text/css" href="estilos.css" />
	</head>
	<body>
		<h1 align="center" >Actualizar Materias Primas</h1>
		<div id="caja" style="text-align:center">
		<div id="lista">
				<div id="titulo"align="left">	
					<input type="button"  class="boton" value="Volver" onclick="window.location.href='Lista_recetas.php'" />	
					<span style="color:white">Actualizar Materias Primas</span>
				</div>
					<table>
					<tr>
					<th>Detalle</th>
					<th>Tipo</th>				
					<th>Cantidad de uso</th>
					<th>Uso por extra</th>
					<th>Es caduco?</th>
					<th>Opciones</th>
					</tr>
					<?php
					foreach($this->matprimas as $mt)
					{
					?>
					<tr>
						<td><a href="Ver_receta.php?id=<?=$mt['id']?>"><?=$mt['d'] ?></a></td>
						<td><?=$mt['tipo'] ?></td>			
						<td><?=$mt['Cantidad_de_uso'] ?></td>							
						<td><?=$mt['Uso_extra'] ?></td>							
						<td><?php if($mt['Es_caduco']=="s")echo"si";else echo"no";?></td>										
						<td>
						<a class="modificar"href="Modificar_Receta.php?id=<?=$mt['id']?>">Mod</a> - 
						<a class="eliminar" href="Eliminar_Receta.php?tipo=mp&id=<?=$mt['id']?>" >X</a>
						</td>			
					</tr>
					<?php
					}
					?>
					</table>

		</div>
		</div>
		<div class="menu">
			<p>Buscar</p>	
			<form action="Admin_MatPrima.php" method="get" align="center">		
					<input type="text" id="minibuscador" maxlength="50" name="buscarreceta"/>
					<input type="submit" class="boton" value="Buscar"/>			
			</form>
		</div>
		<div class="menu">
			<p>Administrar</p>
			<ul>						
			<li><a class="agregar" href="Registro_Receta.php?tipo=mp">Registro Materia Prima</a></li>
			</ul>
		</div>
				<footer>
				<br/>
				SGSR &copy 2016 
				</footer>
	</body>
</html>