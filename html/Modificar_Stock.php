<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"/>
		<title>SGSR - Modificar Stock</title>
		<link rel="stylesheet" type="text/css" href="estilos.css" >
				<script type="text/javascript">
	
				
			function cargarSelect(valor)
			{
				if(valor==0)
				{
					document.getElementById("anot").hidden=true;
					document.getElementById("cantidad1").hidden=true;
					document.getElementById("cantidad2").hidden=true;
					document.getElementById("cantidad3").hidden=true;
					document.getElementById("seccion").hidden=true;
					document.getElementById("enviar").hidden=true;
					document.getElementById("seccionesp").hidden=true;
				}
				else
				{	
					document.getElementById("anot").hidden=false;
					document.getElementById("enviar").hidden=false;
					if(valor==2)
					{
						document.getElementById("cantidad1").hidden=true;
						document.getElementById("cantidad2").hidden=false;
						document.getElementById("cantidad3").hidden=true;
						document.getElementById("seccion").hidden=true;
						document.getElementById("seccionesp").hidden=true;
					}
					else
					{
						if(valor==3)
						{
							document.getElementById("cantidad1").hidden=true;
							document.getElementById("cantidad2").hidden=true;
							document.getElementById("cantidad3").hidden=false;
							document.getElementById("seccion").hidden=false;
							document.getElementById("seccionesp").hidden=true;
						}
						else
						{					
							if(valor==4)
							{
								document.getElementById("cantidad1").hidden=true;
								document.getElementById("cantidad2").hidden=true;
								document.getElementById("cantidad3").hidden=false;
								document.getElementById("seccion").hidden=true;
								document.getElementById("seccionesp").hidden=false;
							}
							else
							{
								document.getElementById("seccion").hidden=true;
								document.getElementById("cantidad1").hidden=false;
								document.getElementById("cantidad2").hidden=true;
								document.getElementById("cantidad3").hidden=true;
								document.getElementById("seccionesp").hidden=true;
							}
						}
					}
				}
		
			}
		</script>
		<style>
		#caja{
		margin-left:13%;
		text-align:center;
		}
		</style>
	</head>
	<body>
		<h1 align="center" >Modificar Stock</h1>
		<div id="caja" >
				<?php
				
				if(!(
				isset($_POST['tipo'])&&
				isset($_POST['cantidad1'])&&
				isset($_POST['cantidad2'])&&
				isset($_POST['cantidad3'])&&
				isset($_POST['sec'])&&
				isset($_POST['disp'])&&
				isset($_POST['anotacion'])
				))
				{
				
				
				
						
				?>
				<form action="Modificar_Stock.php?id=<?=$this->stock['cod_stock'];?>" method="post">
				<br/>
					<select name="tipo" onchange='cargarSelect(this.value);'>
						<option value="0">-Selecciona una accion-</option>
						<option value="1">Registrar Perdida de stock</option>
						<option value="2">Corregir cantidad de stock</option>
						<?php if($_SESSION['ID']==2||$_SESSION['ID']==6){ ?>
						<option value="3">Transladar Stock a otra Seccion</option>
						<?php } ?>
					</select>				
					<div id="cantidad1" hidden="true">
					<br/>	
						Cantidad de stock que se perdio:<br/>
						<input type="number" name="cantidad1" min="1" max="<?=$this->stock['cantidad']?>" />	
					</div>
					<div id="cantidad2" hidden="true">
					<br/>	
						Cantidad de stock a reeplazar:<br/>
						<input type="number" name="cantidad2" min="0"/>	
					</div>
					<div id="cantidad3" hidden="true">
					<br/>	
				
					
						Cantidad de stock que se va a transladar:<br/>
						<input type="number" name="cantidad3" min="1" max="<?=$this->stock['cantidad']?>"  />	
						
					</div>
					<div id="seccion" hidden="true">
					<br/>
					<?php
					if($this->seccion == null)
					{	
					?>
					<p>No hay depositos disponibles para este ingrediente</p>
					<?php
					}else{
					?>
					Seccion donde se va a mover:
					<select name="sec" id="sec">
						<?php
						foreach($this->seccion as $sec)
						{
						?>
						<option value="<?=$sec['id']?>"><?=$sec['s']?> < <?=$sec['c']?> < <?=$sec['d']?></option>
						<?php
						}
						?>
					</select>
					
					<br/>
					Ingrese el estado de la seccion donde se guardo el ingrediente:
					<select name="disp">				
						<option value="Con espacio">Con espacio</option>		
						<option value="Lleno">Lleno</option>		
					</select>
					<?php } ?>
					</div>
					<div id="seccionesp" hidden="true">
					<br/>
					<?php
					if($this->seccionesp == null)
					{	
					?>
					<p>No hay depositos disponibles para este ingrediente</p>
	
							
					<?php
					}else{
					?>
					Seccion donde se va a mover:
					<select name="sec" id="sec">
						<?php
						foreach($this->seccionesp as $sec)
						{
						?>
						<option value="<?=$sec['id']?>"><?=$sec['s']?> < <?=$sec['c']?></option>
						<?php
						}
						?>
					</select>
					
					<br/>
					Ingrese el estado de la seccion donde se guardo el ingrediente:
					<select name="disp">				
						<option value="Con espacio">Con espacio</option>		
						<option value="Lleno">Lleno</option>		
					</select>
					<?php } ?>
					</div>
					<div id="anot" hidden="true">
						<br/>
						
						Anotaciones - (Opcional)</br>
						<textarea name="anotacion"></textarea>
						
					</div>
					<br/><br/>
					<input type="button" class="boton" id="Cancelar" value="Cancelar" onclick="history.go(-1)"/>
					<input type="submit" class="boton" id="enviar" hidden="true" value="Enviar"/>
			
				</form>

				<?php

				}
				elseif(
						(isset($_POST['tipo'])&&isset($_POST['cantidad1'])&&isset($_POST['anotacion']))||
						(isset($_POST['tipo'])&&isset($_POST['cantidad2'])&&isset($_POST['anotacion']))||
						(isset($_POST['tipo'])&&isset($_POST['cantidad3'])&&isset($_POST['anotacion'])&&isset($_POST['sec']))
						)	
				{
				?>
				<p>Registro Completado Exitosamente</p>
				<a href="Lista_Stock.php">Volver al Menu</a>
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