<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"/>
		<title>SGSR - Realizar Receta para Empleado</title>
		<link rel="stylesheet" type="text/css" href="estilos.css" />
		<style>
		#caja{
		margin-left:13%;
		text-align:center;
		}
		</style>
	</head>
	<body>
		<h1 align="center" >Realizar Receta para Empleado</h1>

		<div id="caja" >
				
				<?php if(!isset($_POST['rec'])&&!isset($_POST['cant'])){ ?>
				
				<form action="" method="post">
		
					Receta para Empleado a realizar</br>
					<select name="rec" required>
						<?php foreach($this->recetas as $mp){ ?>
						<option value="<?=$mp['id']?>"><?=$mp['d']?></option>
						<?php } ?>
					</select>
					<br/><br/>
					Cantidad:
					<input type="number" min="1" max="20" value="1" name="cant" required />
					<br/><br/>	
					<input type="button" class="boton" id="Cancelar" value="Cancelar" onclick="history.go(-1)"/>
					<input type="submit" class="boton" value="Enviar"/>	

				</form>		
		
				<?php
				}
				else
				{
				if($this->entregado){
				?>
				<p>Realizacion Completada Exitosamente</p>
				<a href="VerListaPedidos.php">Volver al Menu</a>
				<?php
				}else{
				?>
				<p style="color:red">No hay suficentes ingredientes para realizar la receta</p>
				<a href="VerListaPedidos.php">Volver al Menu</a>
				<?php
				}
				}
				?>
				
		</div>
		<footer>
		<br/>
		SGSR &copy 2016 
		</footer>		
	</body>
</html>