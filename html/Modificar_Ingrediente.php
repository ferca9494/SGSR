<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"/>
		<title>SGSR - Modificar Ingrediente</title>
		<link rel="stylesheet" type="text/css" href="estilos.css" />
		<script>

		function predet()
		{
		var cant = document.getElementById('cant');
		var upe = document.getElementById('upe');
			if(cant.disabled==false)
				cant.disabled=true;
			else
				cant.disabled=false;
			
			if(upe.disabled==false)
				upe.disabled=true;
			else
				upe.disabled=false;
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
		<h1 align="center" >Modificar Ingrediente</h1>

		<div id="caja">
				
				<?php if(!isset($_POST['ing'])&&!isset($_POST['ing_cant'])&&!isset($_POST['ing_upe'])){ ?>
				
				<form action="Modificar_Ingrediente.php?rec=<?=$_GET['rec']?>&reci=<?=$_GET['reci']?>" method="post">
					
					Ingrediente:<br/>
					<select name="ing" id="ingrediente" onchange='cambiarCant(this.value)' required >
								<option value="0">Selecciona ingediente</option>
							<?php foreach($this->ingre as $in){ ?>	
								<option value="<?=$in['id']?>" <?php if($this->este_ingre['reci']==$in['id'])echo"selected"; ?>><?=$in['d']?></option>
							<?php } ?>
					</select>
					<br/><br/>
					Cantidad de uso:<br/>
					<input type="number" min="1" max="20" id="cant" value="<?=$this->este_ingre['cantidad']?>"  name="ing_cant" required />
					<br/><br/>
					Uso por extra:<br/>
					<input type="number" min="0" max="20" id="upe" value="<?=$this->este_ingre['uso_por_extra']?>"   name="ing_upe" required />
					<br/><br/>	
					<input type="checkbox"  name="pre" id="pre" onclick='predet()' value="s"   />
					<label for="pre">Predeterminado</label> 				
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
				<a href="Admin_MatPrima.php">Volver al Menu</a> o 
				<a href="modificar_receta.php?id=<?=$_GET['rec']?>">Seguir Modificando</a>
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