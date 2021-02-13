<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"/>
		<title>SGSR - Registrar Almacen</title>
		<link rel="stylesheet" type="text/css" href="estilos.css" />
		<style>
		#caja{
		margin-left:13%;
		text-align:center;
		}
		</style>
	</head>
	<body>
		<h1 align="center" >Registrar Contenedor</h1>

		<div id="caja" >
				
				<?php 	if(!isset($_POST['detalle'])&&!isset($_POST['dep'])){ ?>
				
				<?php if(isset($_GET['dep'])){ ?>
				<form action="Registro_Contenedor.php?dep=<?=$_GET['dep']?>" method="post">
				<?php }else{ ?>
				<form action="Registro_Contenedor.php" method="post">
				<?php } ?>
					Detalle de contenedor:<br/>
					<input type="text" name="detalle" maxlength="20" required />
					<br/><br/>
					<?php if(!isset($_GET['dep'])){ ?>
					Deposito al que se asigna el contenedor:<br/>
					<select name="dep">
						<?php foreach($this->depositos as $d){ ?>
							<option value="<?=$d['cod_deposito']?>"><?=$d['detalle']?></option>
						<?php } ?>
					</select>
					<br/><br/>
					<?php } ?>
					<input type="button" class="boton" id="Cancelar" value="Cancelar" onclick="history.go(-1)"/>
					<input type="submit" class="boton" value="Enviar"/>	

				</form>		
				<?php
				}
				else
				{
				?>
				<p>Registro Completado Exitosamente</p>
				<a href="Registro_Seccion.php?con=<?=$this->este_cont?>">Registrar las secciones de este contenedor</a>
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