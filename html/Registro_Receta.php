<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"/>
		<title>SGSR - Registrar Receta</title>
		<link rel="stylesheet" type="text/css" href="estilos.css" >
		<script type="text/javascript">
			function cargarSelect(valor)
			{
			
				//var getID = document.getElementById;
				
				if(valor=='')
				{
					document.getElementById("caja_det").hidden=true;
					document.getElementById("enviar").hidden=true;
					document.getElementById("caja_tipoconserva").hidden=true;
					document.getElementById("caja_matproc").hidden=true;
					document.getElementById("caja_canting").hidden=true;
				}
				else
				{			
					document.getElementById("caja_det").hidden=false;
					document.getElementById("enviar").hidden=false;
					if(valor>=1&&valor<=3||valor==6)
					{	
						document.getElementById("caja_matproc").hidden=false;
						document.getElementById("caja_tipoconserva").hidden=false;
						
						document.getElementById("tipo_con").required=true;
						document.getElementById("cant").required=true;
						document.getElementById("extra").required=true;
						
						if(valor==6){
							document.getElementById("caja_canting").hidden=false;
							document.getElementById("canting").required=true;
						}else{
							document.getElementById("caja_canting").hidden=true;
							document.getElementById("canting").required=false;
						}
					}
					else
					{
						if(valor!=7)
						{
							document.getElementById("caja_tipoconserva").hidden=true;
							document.getElementById("caja_matproc").hidden=true;
							document.getElementById("caja_canting").hidden=true;
							
							document.getElementById("tipo_con").required=false;
							document.getElementById("cant").required=false;
							document.getElementById("extra").required=false;						
						}
						else
						{
							document.getElementById("caja_tipoconserva").hidden=false;
							document.getElementById("caja_matproc").hidden=true;
							
							document.getElementById("tipo_con").required=true;
							document.getElementById("cant").required=true;
							document.getElementById("extra").required=true;	
						}
						document.getElementById("caja_canting").hidden=false;
						document.getElementById("canting").required=true;
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
		<h1 align="center" >Registrar Receta</h1>
		
		<div id="caja">
			<?php
			if($this->listo==false)
			{
			?>
				<form action="Registro_Receta.php?tipo=<?=$_GET['tipo']?>" method="post">
					Tipo:<br/>
					<select name="rec_tipo" onchange='cargarSelect(this.value);' required >
						<option value="">Selecciona un tipo</option>
					<?php foreach($this->tipo_mat as $tm){ ?>	
						<option value="<?=$tm['cod_tipo_receta']?>"><?=$tm['detalle']?></option>
					<?php } ?>
					</select>
					
					<div id="caja_det" hidden="true">
					Detalle:<br/>
					<input type="text" name="rec_detalle" maxlength="60" required />
					<br/><br/>
					Anotaciones*<br/>
					<textarea name="rec_anot" maxlength="150" ></textarea>
					</div>
					<br/>
					<div id="caja_tipoconserva" hidden="true">
						Tipo de Conserva:<br/>
						<select name="mp_cat" required="false" id="tipo_con" >
						<?php foreach($this->cat_mat as $cm){ ?>	
							<option value="<?=$cm['cod_tipo_conserva']?>"><?=$cm['detalle']?></option>
						<?php } ?>
						</select>
						<br/><br/>
						<label for="cad">Caducidad:</label>
						<input type="checkbox" name="mp_cad" id="cad" value="s" />
					</div>
					<br/>
					<div id="caja_matproc" hidden="true">	
						Cantidad de uso<br/>
						<input type="number" name="mp_cantuso" min="1" value="1" required="false" id="cant"/>
						<br/><br/>
						Uso por Extra<br/>
						<input type="number" name="mp_uextra" min="0" value="0" required="false" id="extra" />
					</div>
					<br/>
					<div id="caja_canting" hidden="true">			
						Cantidad de Ingredientes:<br/>
						<input type="number" min="1" max="20"  value="1" name="cantingre" required="false" id="canting"/>
						<br/><br/>
					</div>

					<?php if($_GET['tipo']=="mp"){?>
					<input type="button" class="boton" id="Cancelar" value="Cancelar" onclick="window.location.href='Admin_matprima.php'" />
					<?php }elseif($_GET['tipo']=="rec"){ ?>
					<input type="button" class="boton" id="Cancelar" value="Cancelar" onclick="window.location.href='Lista_recetas.php'" />
					<?php } ?>
					<input type="submit" class="boton" id="enviar" hidden="true" value="Enviar"/>
				</form>
				(*)opcional
				<?php
				}
				else
				{
				?>
				<p>Registro Completado Exitosamente</p>
				<?php if($_GET['tipo']=="mp"){?>
				<a href="admin_matprima.php">Volver al Menu</a>
				<?php }elseif($_GET['tipo']=="rec"){?>
				<a href="lista_recetas.php">Volver al Menu</a>
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