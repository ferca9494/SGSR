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
		<h1 align="center" >Modificar Seccion</h1>

		<div id="caja" >
				<br/>
				<?php if(!isset($_POST['con'])&&!isset($_POST['detalle'])&&!isset($_POST['tc'])){?>
				<form action="Modificar_Seccion.php?id=<?=$_GET['id']?>" method="post">
				
					<p>Detalle de seccion</p>
					<input type="text" name="detalle"  maxlength="20" value="<?=$this->seccion['detalle']?>" required />
		
					<br/>
					<p>Tipo de Conserva de la seccion</p>
					<select name="tc">
						<?php foreach($this->tiposC as $d){ ?>
							<option value="<?=$d['cod_tipo_conserva']?>" <?php if($d['cod_tipo_conserva']==$this->seccion['tipo'])echo"selected";?> ><?=$d['detalle']?></option>
						<?php } ?>
					</select>

					<br/>
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