<!DOCTYPE html>

<html>
	<head>
		<meta charset="utf-8"/>
		<title>SGSR - Ingreso de Stock</title>
		<link rel="stylesheet" type="text/css" href="estilos.css" />
		<style>
		#caja{
		margin-left:13%;
		text-align:center;
		}
		</style>
	</head>
	<body>
		<h1 align="center" >Ingreso de Stock</h1>
		<div id="caja">
			
				<?php 
					if(
						(		
							($this->seccion==null&&
							!(
							isset($_POST['mp'])&&
							isset($_POST['cant'])
							))
							||
							($this->seccion!=null&&
							isset($_POST['mp'])&&
							isset($_POST['cant']))	
						)&&
											
							!isset($_POST['dia_ven'])&&
							!isset($_POST['mes_ven'])&&
							!isset($_POST['ano_ven'])&&
							!isset($_POST['sec'])&&
							!isset($_POST['disp'])								
						
					)
					{ ?>
				<form action="Ingreso_Stock.php?tipo=<?=$_GET['tipo']?>" method="post">
					
					<?php
						if(
						isset($_POST['mp'])&&
						isset($_POST['cant'])
						)
						{ ?>
		
					<br/><br/>
					Seccion donde guardar el ingrediente:
					<select name="sec">
					<?php foreach($this->seccion as $sec){ ?>	
						<option value="<?=$sec['id']?>"><?=$sec['s']?> > <?=$sec['c']?> > <?=$sec['d']?></option>
					<?php } ?>
					</select>
					<br/><br/>
					Ingrese el estado de la seccion donde se guardo el ingrediente:
					<select name="disp">
						<option value="Con espacio">Con espacio</option>
						<option value="Lleno">Lleno</option>
					</select>
					<?php if($this->escaduco==true){ ?>
					<br/><br/>
					Ingrese la Fecha de Vencimiento del ingrediente:<br/>
					<select name="dia_ven">
					<?php for($i=1;$i<32;$i++){ ?>
						<option value="<?=$i?>"><?=$i?></option>
					<?php } ?>
					</select>
					<select name="mes_ven">
					<?php for($i=1;$i<13;$i++){ ?>
						<option value="<?=$i?>"><?=$i?></option>
					<?php } ?>
					</select>
					<select name="ano_ven">
					<?php for($i=date("Y");$i<date("Y")+3;$i++){ ?>
						<option value="<?=$i?>"><?=$i?></option>
					<?php } ?>
					</select>
					<?php } ?>
					<br/><br/>
					Anotaciones*<br/>
					<textarea name="anotacion"></textarea>
					<br/><br/>
					
					<input type="hidden" name="mp" value="<?=$_POST['mp']?>">
					<input type="hidden" name="cant" value="<?=$_POST['cant']?>">
					<input type="button" class="boton" id="Cancelar" value="Cancelar" onclick="history.go(-1)"/>
					<input type="submit" class="boton" value="Enviar"/>
					<?php
					
					}
					else				
					{
					//1
					?>
					<br/><br/>
					Ingrediente a guardar:<br/>
					<select name="mp" >
					<?php foreach($this->mat_prima as $mp){ ?>	
						<option value="<?=$mp['id']?>"><?=$mp['d']?></option>
					<?php } ?>
					</select>
					<br/><br/>
					Cantidad:<br/>
					<input type="number" name="cant" min="1" max="1000" value="1"  />
					<br/><br/>	
					<?php if($_SESSION['ID']==5){ ?>				
					<input type="button" class="boton" id="Cancelar" value="Cancelar" onclick="window.location.href='logout.php'" />
					<?php }elseif($_SESSION['ID']==4){ ?>
					<input type="button" class="boton" id="Cancelar" value="Cancelar" onclick="history.go(-1)"/>
					<?php }elseif($_SESSION['ID']==6){ ?>
					<input type="button" class="boton" id="Cancelar" value="Cancelar" onclick="history.go(-1)"/>
					<?php } ?>		
					<input type="submit" class="boton" value="Enviar"/>
					<?php
					}
					?>
				</form>	
				(*)opcional
				<?php
				}	
				elseif(
					$this->seccion!=null&&
					isset($_POST['mp'])&&
					isset($_POST['cant'])&&
					(
					(
						isset($_POST['dia_ven'])&&
						isset($_POST['mes_ven'])&&
						isset($_POST['ano_ven'])&&
						isset($_POST['sec'])&&
						isset($_POST['disp'])
					)||
					(
						isset($_POST['sec'])&&
						isset($_POST['disp'])
					)
					)
				)
				{
				?>
				<p>Registro Completado Exitosamente</p>
				<?php if($_SESSION['ID']==5){ ?>				
				<a href="logout.php">Cerrar sesion</a> 
				<?php }elseif($_SESSION['ID']==4){ ?>
				<a href="VerListaPedidos.php">Volver a Pedidos</a>
				<?php }elseif($_SESSION['ID']==6){ ?>
				<a href="VerListaPedidos.php">Volver a Pedidos</a>
				<?php } ?>
				<?php
				}
				elseif(
					$this->seccion==null&&
					isset($_POST['mp'])&&
					isset($_POST['cant'])
				)
				{
				?>
				<p style="color:red">No hay depositos disponibles para este ingrediente</p>

				<a href="ingreso_stock.php?tipo=<?=$_GET['tipo']?>">Volver</a>


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