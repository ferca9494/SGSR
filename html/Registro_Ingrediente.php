<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"/>
		<title>SGSR - Registrar Ingredientes</title>
		<link rel="stylesheet" type="text/css" href="estilos.css" />
		<script>
			function predet(pre)
			{
				var cant = document.getElementById('cant_'+pre);
				var upe = document.getElementById('upe_'+pre);
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
		<h1 align="center" >Registrar Ingredientes de receta</h1>
		
		<div id="caja" >
		
				<form action="Registro_Receta.php?tipo=<?=$_GET['tipo']?>" method="post">	
				
				<table align="center">
					<tr>
						<th>Ingrediente</th>
						<th>Cantidad</th>
						<th>Uso por Extra</th>
						<th>Predeterminado</th>
					</tr>
							<?php
							for($i=1;$i<=$_POST['cantingre'];$i++)
							{
							?>
							<tr>
							<td>
							<select name="ing<?=$i?>" id='ingrediente' required >
								<option value="">Selecciona ingrediente</option>
							<?php foreach($this->ingre as $in){ ?>	
								<option value="<?=$in['id']?>"><?=$in['d']?></option>
							<?php } ?>
							</select>
							</td>
							<td><input type="number" min="1" max="20" value="1" name="ing_cant<?=$i?>" id="cant_<?=$i?>" disabled="true" required /></td>
							<td><input type="number" min="0" max="20" value="0" name="ing_upe<?=$i?>" id="upe_<?=$i?>" disabled="true" required /></td>
							<td align ="center"><input type="checkbox" name="ing_pre<?=$i?>" onclick='predet(<?=$i?>)' value="s" checked /></td>
							</tr>
							<?php
							}
							?>
				</table>							
					<br/>
					<input type="hidden" name="rec_tipo2" value="<?=$_POST['rec_tipo']?>"/>
					<input type="hidden" name="rec_anot2" value="<?=$_POST['rec_anot']?>"/>
					<input type="hidden" name="rec_detalle2" value="<?=$_POST['rec_detalle']?>"/>
					
					<?php if($_POST['rec_tipo']==6) { ?>
					<input type="hidden" name="mp_cantuso2" value="<?=$_POST['mp_cantuso']?>"/>	
					<input type="hidden" name="mp_uextra2" value="<?=$_POST['mp_uextra']?>"/>
					<?php } ?>
					<?php if($_POST['rec_tipo']==7||$_POST['rec_tipo']==6) { ?>
					<input type="hidden" name="mp_cat2" value="<?=$_POST['mp_cat']?>"/>		
					<input type="hidden" name="mp_cad2" value="<?=$_POST['mp_cad']?>"/>
					<?php } ?>
					<input type="hidden" name="cantingre2" value="<?=$_POST['cantingre']?>"/>
					
					<input type="button" class="boton" id="Cancelar" value="Cancelar" onclick="history.go(-1)"/>
					<input type="submit" class="boton" value="Enviar"/>
				</form>
			
		</div>
				<footer>
				<br/>
				SGSR &copy 2016 
				</footer>
	</body>
</html>