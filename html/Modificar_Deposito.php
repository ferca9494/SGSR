<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"/>
		<title>SGSR - Modificar Almacen</title>
		<link rel="stylesheet" type="text/css" href="estilos.css" />
		<style>
		#caja{
		margin-left:13%;
		text-align:center;
		}
		</style>
	</head>
	<body>
		<h1 align="center" >Modificar Deposito</h1>

		<div id="caja" >
				
				<?php if(!isset($_POST['detalle'])){ ?>
				
				<form action="Modificar_Deposito.php?id=<?=$_GET['id']?>" method="post">
		
					Detalle de deposito:<br/>
					<input type="text" name="detalle" maxlength="20" value="<?=$this->deposito['detalle']?>" required />
					
					<br/><br/>	
					<input type="button" class="boton" id="Cancelar" value="Cancelar" onclick="history.go(-1)"/>
					<input type="submit" class="boton" value="Enviar"/>	

				</form>		
		
				<?php
				}
				else
				{
				?>
				<p>Registro Completado Exitosamente</p>
				<a href="Admin_Almacen.php">Volver al Menu</a>
				<?php
				}
				?>
				</div>
				<footer>
		<br/>
		SGSR &copy 2016 
		</footer>				
		
	</body>
</html>