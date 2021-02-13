<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"/>
		<title>SGSR - Lista de Pedidos</title>
		<link rel="stylesheet" type="text/css" href="estilos.css" />
		<style>
			td{text-align:center}
		</style>
		<script>
			setTimeout( function() { location.reload(); }  , 30000  );
		</script>
	</head>
	<body>
		<h1 align="center" >Lista de Pedidos</h2>
		<div id="pestanas">
			<?php if($_SESSION['ID']!=4){?>
			<a href="Lista_Recetas.php">Recetas</a>
			<?php } ?>
			<a id="act" href="VerListaPedidos.php">Pedidos</a>
			<?php if($_SESSION['ID']!=4){?>
			<a href="Lista_Stock.php">Stock</a>			
			<?php } ?>
			<?php if($_SESSION['ID']==1||$_SESSION['ID']==3||$_SESSION['ID']==6){?>
			<a href="HistorialStock.php">Historial de Stock</a>
			<?php  } ?>
		</div>
		
		<div id="caja">
			
			<div id="lista">
				<?php
				if(count($this->pedidos)==0)
				{ ?>
				<p align="center">No hay Pedidos<br/><br/>
				<a class="agregar" href="AltaPedido.php">Agregar</a>
				</p>
				<?php 
				}
				else
				{
				?>
				<table>
				<tr>
				<th>Detalle</th>
				<th>Cantidad</th>
				<th>Extra?</th>
				<th>Esta?</th>
				<?php if($_SESSION['ID']==2||$_SESSION['ID']==6){?>
				<th>Opciones</th>
				<?php } ?>
				</tr>
				<?php
				foreach($this -> pedidos as $p)
				{
				?>
				<tr>
				<td><a href="Ver_Receta.php?id=<?=$p['cod_receta']?>"> <?= $p ['detalle'] ?> </td>
				<td> <?= $p ['cantidad'] ?> </td>
				<td> <?php if( $p ['pidio_extra']=='s')echo"si";else echo"no"; ?> </td>
				<td> <?php if( $p ['esta_entregado']=='s')echo"si";else echo"no"; ?> </td>
				<?php if($_SESSION['ID']==2||$_SESSION['ID']==6){?>
				<td><a href="Realizar_Pedido.php?id=<?= $p ['cod_pedido'] ?> ">Hecho</td>
				<?php } ?>
				</tr>
				<?php
				}
				?>
				</table><?php } ?>
			</div>
			
		</div>
		<?php if($_SESSION['ID']==4||$_SESSION['ID']==6){?>
		<div class="menu">
			<p>Pedido o Retiro</p>
			<ul>
			<li><a href="AltaPedido.php">Pedir Receta</a></li>
			<li><a href="AltaPedidoMP.php">Retiro de MatPrima</a></li>
			<li><a href="AltaPedidoPD.php">Retiro de PlatoDepositado</a></li>
			<?php if($_SESSION['ID']==6){?>
			<li><a href="Ingreso_Stock.php?tipo=ing">Ingreso de Stock</a></li>
			<?php } ?>
			<li><a href="Ingreso_Stock.php?tipo=dev">Devolucion</a></li>
			</ul>
		</div>
		<?php } ?>
		<?php if($_SESSION['ID']==2||$_SESSION['ID']==6){?>
		<div class="menu">
			<p>Realizacion</p>
			<ul>
			<li><a href="Realizar_RecetaMatPro.php">Realizar una materia procesada</a></li>
			<li><a href="Realizar_RecetaADep.php">Realizar una receta a depositar</a></li>
			<li><a href="Realizar_RecetaEmpl.php">Realizar una receta para empleado</a></li>
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