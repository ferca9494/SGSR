<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"/>
		<title>SGSR - Retiro de Materia Prima</title>
		<link rel="stylesheet" type="text/css" href="estilos.css" />	
		<style>
		#caja{
		margin-left:13%;
		text-align:center;
		}
		</style>
	</head>

	<body>
		<h1> Retiro de Plato depositado </h1>
		<div id="caja" >
		<?php
		if(!ISSET ($_POST['cod_receta'])&&!isset($_POST['cantidad'])) {
		?>
		<form action = "" method = "post">
		<br />
			Plato a retirar:
			<select name="cod_receta" required >
			<?php foreach($this->recetas as $r) {?>
				<option value="<?=$r['cod_receta']?>"> <?= $r['detalle'] ?> </option>
			<?php } ?>
			</select>
			<br /> <br />	
			Cantidad:
			<input type = "number" min="1"  max="20" name="cantidad" required />

			
			<br /> <br />
			<input type="button" class="boton" id="Cancelar" value="Cancelar" onclick="history.go(-1)"/> 
			<input type="submit" class="boton" value="Enviar" />
			
		</form>
		<?php
		}
		else
		{
		?>
		
		<?php if($this->entregado){ ?>
		<p>Retiro realizado Exitosamente</p>
		<a href="VerListaPedidos.php">Volver a la Lista de Pedidos</a>
		<?php }else{ ?>
		<p style="color:red">No existe esa cantidad de objetos en los almacenes</p>
		<a href="VerListaPedidos.php">Volver al Menu</a>
		<?php } ?>
		
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