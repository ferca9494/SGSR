<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"/>
		<title>SGSR - Realizar <?=$this->titulo?></title>
		<link rel="stylesheet" type="text/css" href="estilos.css" />
		<style>
		#caja{
		margin-left:13%;
		text-align:center;
		}
		</style>
	</head>
	<body>
		<h1 align="center" >Realizar <?=$this->titulo?></h1>

		<div id="caja" >
				<form action="" method="post">
				<?php if(!isset($_POST['rec'])&&!isset($_POST['cant'])){ ?>
				
				
		
		
					<?=$this->titulo?> a realizar</br>
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
					
					
				<?php
				}
				else
				{
				
				if(!(isset($_POST['seccion'])&&isset($_POST['disp'])))
				{
					if($this->verificar){
				?>
						Seccion donde se va a mover:
						<select name="seccion" required>
							<?php foreach($this->sec as $sec)	{?>
							<option value="<?=$sec['id']?>"><?=$sec['d']?>><?=$sec['c']?>><?=$sec['s']?></option>
							<?php	}?>
						</select>
						<br/><br/>
						Ingrese el estado de la seccion donde se guardo el ingrediente:
						<select name="disp" required>
							<option value="Con espacio">Con espacio</option>
							<option value="Lleno">Lleno</option>
						</select>
						<br/><br/>				
						<?php if($this->rec['Es_caduco']=='s'){ ?>
						Fecha de Vencimiento:<br/>
						<select name="dia_ven" required><?php for($i=1;$i<32;$i++){ ?><option value="<?=$i?>"><?=$i?></option>	<?php } ?></select>
						<select name="mes_ven" required><?php for($i=1;$i<13;$i++){ ?>	<option value="<?=$i?>"><?=$i?></option>	<?php } ?></select>
						<select name="ano_ven" required><?php for($i=date("Y");$i<date("Y")+3;$i++){ ?><option value="<?=$i?>"><?=$i?></option><?php } ?></select>
						<br/><br/>
						<?php } ?>
						Anotaciones - (Opcional)</br>
						<textarea name="anotacion" ></textarea>
						<br/><br/>
				
						<input type="hidden" name="rec" value="<?=$_POST['rec']?>"/>				
						<input type="hidden" name="cant" value="<?=$_POST['cant']?>"/>

						<input type="button" class="boton" id="Cancelar" value="Cancelar" onclick="history.go(-1)"/>
						<input type="submit" class="boton" id="enviar"  value="Enviar" />		
						
				</form>		
		
				<?php
					}else{
				?>
				<p style="color:red">No hay suficentes ingredientes para realizar la receta</p>
				<a href="VerListaPedidos.php">Volver al Menu</a>
				<?php
				}
				
				}
				else
				{
				?>
				<p>Realizacion Completada Exitosamente</p>
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