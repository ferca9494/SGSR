
<html>
	<head>
		<meta charset="utf-8"/>
		<title>SGSR - Modificar Receta</title>
		<link rel="stylesheet" type="text/css" href="estilos.css" />
		<style>
		#caja{
		text-align:center;
		<?php	 if($this->esta_receta['tipo_receta']>=1&&$this->esta_receta['tipo_receta']<=3){	?>
		margin-left:13%;
		<?php } ?>
		}
		.menu{
		text-align:center;
		}
		</style>
	</head>
	<body>
		<h1 align="center" >Modificar Receta</h1>
		
		<div id="caja">
			<?php

			if($this->listo==false)
			{		
			?>
				<form action="Modificar_Receta.php?id=<?= $_GET['id'] ?>" method="post">
					<?php if($this->esta_receta['tipo_receta']!=7&&$this->esta_receta['tipo_receta']!=6){?>
					Tipo:<br/>
					<select name="rec_tipo" onchange='cargarSelect(this.value);' >
					<?php foreach($this->tipo_mat as $tm)
					{ ?>	
						<option value="<?=$tm['cod_tipo_receta'] ?>" <?php if($this->esta_receta['tipo_receta']==$tm['cod_tipo_receta'])echo" selected "; ?>>
						<?=$tm['detalle']?></option>
					<?php } ?>
					<?php } ?>
					</select>
					
					<div id="caja_det">
					Detalle:<br/>
					<input type="text" name="rec_detalle" maxlength="60" value="<?=$this->esta_receta['nombre']?>" required />
					<br/>
					Anotaciones*<br/>
					<textarea name="rec_anot"><?=$this->esta_receta['Anotacion']?></textarea>
					</div>
					<br/>
					<?php if(($this->esta_receta['tipo_receta']>=1&&$this->esta_receta['tipo_receta']<=3)||$this->esta_receta['tipo_receta']==7){ ?>
					<div id="caja_tipoconserva" >
						Tipo de Conserva: 
						<select name="mp_cat" required >
						<?php foreach($this->cat_mat as $cm){ ?>	
							<option value="<?=$cm['cod_tipo_conserva']?>" <?php if($cm['cod_tipo_conserva']==$this->esta_receta['cod_tipo_conserva'])echo"selected";?>><?=$cm['detalle']?></option>
						<?php } ?>
						</select>
					</div>
					<?php } ?>
					<?php if(($this->esta_receta['tipo_receta']>=1&&$this->esta_receta['tipo_receta']<=3)||$this->esta_receta['tipo_receta']==6){ ?>
					<br/>
					<div id="caja_matproc" >
						<label for="cad">Caducidad: </label>
						<input type="checkbox" name="mp_cad" id="cad" <?php if($this->esta_receta['Es_caduco']=="s")echo"checked"; ?> />
						<br/><br/>
						Cantidad de uso<br/>
						<input type="number" name="mp_cantuso" min="1" value="<?=$this->esta_receta['Cantidad_de_uso']?>"   />
						<br/><br/>
						Uso por Extra<br/>
						<input type="number" name="mp_uextra" min="0" value="<?=$this->esta_receta['Uso_extra']?>" />
					</div>
					<br/>
					<?php } ?>

					<br/>
					<?php if($this->esta_receta['tipo_receta']>=1&&$this->esta_receta['tipo_receta']<=3){ ?>
					<input type="button" class="boton" id="Cancelar" value="Cancelar" onclick="window.location.href='Admin_matprima.php'" />
					<?php }else{ ?>
					<input type="button" class="boton" id="Cancelar" value="Cancelar" onclick="window.location.href='Lista_recetas.php'" />
					<?php } ?>
					<input type="submit" class="boton" id="enviar" value="Enviar"/>
				</form>
				(*)opcional
				<?php
				}
				else
				{
				?>
				<p>Registro Modificado Exitosamente</p>
				<?php if($this->esta_receta['tipo_receta']>=1&&$this->esta_receta['tipo_receta']<=3){ ?>
				<a href="Admin_MatPrima.php">Volver al Menu</a>
				<?php }else{ ?>
				<a href="Lista_Recetas.php">Volver al Menu</a>
				<?php } ?>
				<?php
				}
				?>
		</div>
		
					<?php if($this->esta_receta['tipo_receta']>=4&&$this->esta_receta['tipo_receta']<=7){ ?>
					<div id="caja_canting" class="menu" >
						<table  align="center">
						<tr>
						<th>Detalle</th>
						<th>Cantidad a usar</th>
						<th>Uso por Extra</th>
						<th>Opciones</th>
						</tr>
						<?php
						foreach($this->ing_esta_receta as $i)
						{
						?>
						<tr>
						<td><?php if($i['tipo']==6){ ?><a href="Ver_Receta.php?id=<?=$i['id']?>"><?php echo$i['det']; ?></a><?php }else echo$i['det']; ?></td>
						<td><?= $i['cpu'] ?></td>
						<td><?= $i['upe'] ?></td>
						<td><a class="modificar" href="Modificar_Ingrediente.php?rec=<?=$this->esta_receta['cod_receta']?>&reci=<?=$i['id']?>">Mod</a></td>
						</tr>
						<?php
						}
						?>		
						</table>
					</div>
					<?php } ?>
		
		
				<footer>
				<br/>
				SGSR &copy 2016 
				</footer>
	</body>
</html>