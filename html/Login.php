<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8"/>
		<title>SGSR - Login</title>
		<link rel="stylesheet" type="text/css" href="estilos.css" />
		<style>
		#caja{
			min-height:0;
			padding:10px
		}
		</style>
	</head>
	<body>
		<h1 align="center">Gestion de Stock</h1>
		<div id="caja"  style="width:97%;text-align:center">
		<form action="Login.php" method="post">
			Usuario:
			<input type="text" name="nombre" />	
			Contraseña:
			<input type="password" name="pass" />	
			<input type="submit" class="boton" value="Entrar"/>
			<?php
			if(isset($_POST['pass']))
			{	
			?>
			<p style="color:red;">Contraseña invalida</p>
			<?php
			}
			?>
		</form>
		</div>
		<div style="width:97%;text-align:center;background:#F4BC81;float:left;padding:10px;margin:5px">
		<table style="width:100%">
		<tr>
		<th>Usuario</th><th>Contraseña</th><th>Descripcion</th>
		</tr>
		
		<tr><td>due</td><td>d</td><td>El dueño</td></tr>
		<tr><td>c</td><td>c</td><td>El chef</td></tr>
		<tr><td>g</td><td>g</td><td>El gerente</td></tr>
		<tr><td>m</td><td>m</td><td>El mesero</td></tr>
		<tr><td>a</td><td>a</td><td>un asistente</td></tr>
		<tr><td>admin</td><td>314159265</td><td>Usuario Maestro(tiene todo los permisos)</td></tr>
		</table>
		</div>
				<footer>
				<b style="color:white">
				Hecho por Fernando Cañete y Gary Gutierrez
				<br/><br/>
				SGSR &copy 2016 
				</b>
				</footer>
	</body>
</html>