<?php	
$ld = $this->listadeposito;
$lc = $this->listacontenedor;
$ls = $this->listaseccion;
$lst= $this->listastock;
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"/>
		<title>SGSR - Actualizar Almacenes</title>
		<link rel="stylesheet" type="text/css" href="estilos.css" />
		<style>
		td{
		text-align:center
		}
		#buscador
		{
		width:79.5%;
		}
		</style>
		<script>
		function button(spnum){
				var button = document.getElementById('spoiler_' + spnum);
				var contenido = document.getElementById('contenido_' + spnum);
				if(contenido.hidden == true){
				  contenido.hidden = false;}
				else if(contenido.hidden == false){
				  contenido.hidden = true;}
			}
		</script>
	</head>
	<body>
		<h1 align="center" >Actualizar Almacenes</h1>
		<div id="caja">
			<div id="lista">
				<div id="titulo">
					<input type="button"  class="boton" value="Volver" onclick="window.location.href='Lista_stock.php'" />
					<span style="color:white">Actualizar Almacenes</span>
				</div>
				<?php	
				if($ld==null)
					echo " - No hay Depositos - ";
				else{	
				?>
				<ul>
				<?php
				foreach($ld as $dep)
				{
				?>
					<li>
					<button  id="spoiler_<?=$dep['cod_deposito']?>" class="boton" onclick='button(<?=$dep['cod_deposito']?>)'>+</button>
					<b>Deposito <?= $dep['detalle'] ?></b>
					(<a class="agregar"  href="Registro_Contenedor.php?dep=<?=$dep['cod_deposito']?>">+</a> - <a class="modificar" href="Modificar_Deposito.php?id=<?=$dep['cod_deposito']?>">m</a> - <a class="eliminar"  href="Eliminar_Deposito.php?id=<?=$dep['cod_deposito']?>">x</a>)				
					</li>				
					<?php	
					if($lc->getdedeposito($dep['cod_deposito'])==null)
						echo " - No hay Contenedores - ";
					else{	
					?>
					<ul id="contenido_<?=$dep['cod_deposito']?>" hidden="false">
					<?php
					foreach($lc->getdedeposito($dep['cod_deposito']) as $con)
					{
					?>	
						<li>
						<b>Contenedor <?= $con['detalle'] ?></b>
						(<a class="agregar"  href="Registro_Seccion.php?con=<?=$con['cod_contenedor']?>">+</a> - <a class="modificar" href="Modificar_Contenedor.php?id=<?=$con['cod_contenedor']?>">m</a> - <a class="eliminar"  href="Eliminar_Contenedor.php?id=<?=$con['cod_contenedor']?>">x</a>)
						</li>				
						<?php	
						if($ls->getdecont($con['cod_contenedor'])==null)
							echo " - No hay Secciones - ";
						else{	
						?>
						<ul>
						<?php
						foreach($ls->getdecont($con['cod_contenedor']) as $sec)
						{
						?>
						<li>
						<b>Seccion <?= $sec['det'] ?></b> (<i><?= $sec['tipo'] ?></i>)
						(<a class="modificar" href="Modificar_Seccion.php?id=<?=$sec['cod_seccion']?>">m</a> - <a class="eliminar"  href="Eliminar_Seccion.php?id=<?=$sec['cod_seccion']?>">x</a>)
						</li>							
						<?php	} ?>
						</ul>
						<?php } ?>
					<?php	} ?>
					</ul>
					<?php } ?>
				<?php	} ?>
				</ul>
				<?php } ?>		
			</div>
		</div>

		<div class="menu">
			<p>Administrar</p>
			<ul>
			<li><a class="agregar"  href="Registro_Deposito.php">Agregar Deposito</a></li>
			<li><a class="agregar"  href="Registro_Contenedor.php">Agregar Contenedor</a></li>
			<li><a class="agregar"  href="Registro_Seccion.php">Agregar Seccion</a></li>
			</ul>
		</div>
				<footer>
				<br/>
				SGSR &copy 2016 
				</footer>
	</body>
</html>