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
		<h1 align="center" >Modificar Contenedor</h1>

		<div id="caja" >
				
				<?php 	if(!isset($_POST['detalle'])&&!isset($_POST['dep'])){ ?>
				
				<form action="Modificar_Contenedor.php?id=<?=$_GET['id']?>" method="post">
				
					Detalle de contenedor:<br/>
					<input type="text" name="detalle" maxlength="20" value="<?=$this->contenedor['detalle']?>" required />
					<br/><br/>
					<?php if(!isset($_GET['dep'])){ ?>
					Deposito al que se asigna el contenedor:<br/>
					<select name="dep">
						<?php foreach($this->depositos as $d){ ?>
							<option value="<?=$d['cod_deposito']?>" <?php if($d['cod_deposito']==$this->contenedor['cod_deposito'])echo"selected";?> ><?=$d['detalle']?></option>
						<?php } ?>
					</select>
					<br/><br/>
					<?php } ?>
					<input type="button" id="Cancelar" class="boton" value="Cancelar" onclick="history.go(-1)"/>
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