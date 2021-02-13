<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"/>
		<title>SGSR - Lista de Recetas</title>
		<link rel="stylesheet" type="text/css" href="estilos.css" />
		<style>
		td{text-align:center}
		#buscador{
			width:79.5%;
			}
		</style>
		<script>
					function cargarSelect(valor)
					{
							if(valor==3)
							{
								document.getElementById("minibuscador").disabled=true;
								document.getElementById("tipo").hidden=false;
							}
							else
							{
								document.getElementById("minibuscador").disabled=false;
								document.getElementById("tipo").hidden=true;
							}
					}
		</script>		
	</head>
	<body>
		<h1 align="center" >Lista de Recetas</h2>
		<div id="pestanas">
			<a id="act" href="Lista_Recetas.php">Recetas</a>
			<?php if($_SESSION['ID']==4||$_SESSION['ID']==2||$_SESSION['ID']==6){?>
			<a href="VerListaPedidos.php">Pedidos</a>
			<?php } ?>
			<?php if($_SESSION['ID']!=4){?>
			<a href="Lista_Stock.php">Stock</a>
			<?php } ?>
			<?php if($_SESSION['ID']==1||$_SESSION['ID']==3||$_SESSION['ID']==6){?>
			<a href="HistorialStock.php">Historial de Stock</a>
			<?php  } ?>
		</div>
		
		<div id="caja">
			<div id="titulo">
					<span style="color:white">Lista de Recetas</span>
						<?php if(isset($_GET['filtro'])){?>
						<?php if($_GET['filtro']==1){?>
							<span style="color:white">(busqueda por receta)</span>
						<?php }elseif($_GET['filtro']==2){?>
							<span style="color:white">(busqueda por ingrediente)</span>
						<?php }elseif($_GET['filtro']==3){?>
							<span style="color:white">(busqueda por tipo)</span>
						<?php } ?>
					<?php } ?>
				</div>
			<div id="lista">
				<?php
				if(count($this->listareceta)==0)
				{ ?>
				<p>No hay Recetas</p><br/>
				<a class="agregar" href="Registro_Receta.php">Agregar</a>
				<?php 
				}
				else
				{
				?>
				
				<table>
				<tr>
				<th>Detalle</th>
				<th>Tipo</th>
				<?php if($_SESSION['ID']==1||$_SESSION['ID']==6){?>
				<th>Opciones</th>
				<?php } ?>
				</tr>
				<?php
				foreach($this->listareceta as $r)
				{
				?>
				<tr>
				<td><b><a href="Ver_Receta.php?id=<?=$r['id']?>"><?= $r['d'] ?></a></b></td>
				<td><?= $r['tipo'] ?></td>
				<?php if($_SESSION['ID']==1||$_SESSION['ID']==6){?>
				<td>
					<a class="modificar"href="Modificar_Receta.php?id=<?=$r['id']?>">Mod</a> - 
					<a class="eliminar" href="Eliminar_Receta.php?tipo=rec&id=<?=$r['id']?>" >X</a>
				</td>
				<?php } ?>
				</tr>
				<?php
				}
				?>
				</table>
				<?php } ?>
			</div>
			
		</div>
		<div class="menu">
			<p>Buscar</p>	
			<form action="" method="get" align="center">
					<input type="text" id="minibuscador" maxlength="50" name="buscarreceta"/>
					<input type="submit" class="boton" value="Buscar"/>		
					<br/>			
					<select style="margin:10px" name="filtro" id="filtro" onchange='cargarSelect(this.value);'>
						<option value="1">Receta:</option>
						<option value="2">Ingrediente:</option>
						<option value="3">Tipo:</option>
					</select>
					<br/>
					<select style="margin:10px" name="filtro_tipo" id="tipo" hidden="true">
						<?php foreach($this->tipos as $t){ ?>
						<option value="<?=$t['cod_tipo_receta']?>"><?=$t['detalle']?></option>
						<?php } ?>
					</select>				
			</form>
		</div>
		<?php if($_SESSION['ID']==1||$_SESSION['ID']==6){?>
		<div class="menu">
			<p>Administrar</p>
			<ul>						
			<li><a class="agregar" href="Registro_Receta.php?tipo=rec">Registro Receta</a></li>
			</ul>
		</div>
		<?php } ?>
		<div class="menu">
			<p>Opciones</p>
			<ul>						
			<?php if($_SESSION['ID']==1||$_SESSION['ID']==6){?>
			<li><a href="Admin_MatPrima.php">Actualizar Materia Prima</a></li>
			<li><a href="Admin_Almacen.php">Actualizar Almacenes</a></li>	
			<?php } ?>
			<li><a href="logout.php">Cerrar Sesion</a></li>
			</ul>
		</div>
		<footer>
		<br/>
		SGSR &copy 2016 
		</footer>
	</body>
</html>