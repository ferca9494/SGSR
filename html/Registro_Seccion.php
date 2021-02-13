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
		<h1 align="center" >Registrar Seccion</h1>

		<div id="caja">
				<br/>
				<?php 
				$flag=false;
				if(isset($_POST['cant2']))
				{				
					$cont=0;
					for($i=1;$i<=$_POST['cant2'];$i++)
					{
						if(isset($_POST['tc_'.$i])&&isset($_POST['detalle_'.$i]))
						{
							$cont++;
						}
					}
					
					if($cont==$i-1&&$cont!=0)
					{
						$flag=true;
					}
				}
				
				if(!isset($_POST['cant'])&&!isset($_POST['con2'])&&!isset($_POST['cant2'])&&$flag==false){ ?>
				
				<?php if(isset($_GET['con'])){ ?>
				<form action="Registro_Seccion.php?con=<?=$_GET['con']?>" method="post">
				<?php }else{ ?>
				<form action="Registro_Seccion.php" method="post">
				<?php } ?>
					
					<?php if(!isset($_POST['cant'])){ ?>
					
					<?php if(!isset($_GET['con'])){ ?>
					Contenedor al que se asigna la seccion:<br/>
					<select name="con">
						<?php foreach($this->contenedores as $d){ ?>
							<option value="<?=$d['id']?>"><?=$d['dep']?>><?=$d['con']?></option>
						<?php } ?>
					</select>
					<br/><br/>
					<?php } ?>
					
					Cantidad de secciones a agregar:
					<select name="cant">
						<?php for($i=1;$i<=20;$i++){ ?>
							<option value="<?=$i?>"><?=$i?></option>
						<?php } ?>
					</select>
					<br/>
					<?php }else {?>
					
					
					<?php } ?>
					<br/>
					<?php if(!isset($_GET['con'])){ ?>
					<input type="button" id="Cancelar"  class="boton" value="Cancelar" onclick="history.go(-1)"/>
					<?php }else{ ?>
					<input type="button"id="Cancelar"  class="boton" value="Cancelar" onclick="window.location.href='admin_almacen.php'" />
					<?php } ?>
					<input type="submit" class="boton"  value="Enviar"/>	

				</form>		
				<?php
				}
				elseif(isset($_POST['cant']))
				{
				?>	
				<?php if(isset($_GET['con'])){ ?>
				<form action="Registro_Seccion.php?con=<?=$_GET['con']?>" method="post">
				<?php }else{ ?>
				<form action="Registro_Seccion.php" method="post">
				<?php } ?>
				<table align="center">
					<tr>
					<th>Detalle de seccion</th>
					<th>Tipo de Conserva de la seccion</th>
					</tr>
					
					<?php for($i=1;$i<=$_POST['cant'];$i++){ ?>
					<tr>
					<td>
					<input type="text" name="detalle_<?=$i?>" maxlength="20" required />
					</td>
					<td>
					<select name="tc_<?=$i?>">
						<?php foreach($this->tiposC as $d){ ?>
							<option value="<?=$d['cod_tipo_conserva']?>"><?=$d['detalle']?></option>
						<?php } ?>
					</select>
					</td>
					</tr>
					<?php } ?>
					</table>
					<input type="hidden" name="con2" value="<?=$_POST['con']?>"/>
					<input type="hidden" name="cant2" value="<?=$_POST['cant']?>"/>
					<br/>
					<?php if(!isset($_GET['con'])){ ?>
					<input type="button" class="boton" id="Cancelar" value="Cancelar" onclick="history.go(-1)"/>
					<?php }else{ ?>
					<input type="button" class="boton" id="Cancelar" value="Cancelar" onclick="window.location.href='admin_almacen.php'" />
					<?php } ?>
					
					<input type="submit" class="boton" value="Enviar"/>	
					</form>
				<?php
				}
				elseif(isset($_POST['con2'])&&isset($_POST['cant2'])&&$flag==true)
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