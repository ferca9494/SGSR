<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"/>
		<title>SGSR - Informe de Registros de Movimientos</title>
		<link rel="stylesheet" type="text/css" href="estilos.css" />
		<style>
		td{text-align:center}
		#buscador{
width:73.5%;
}
		</style>
		<script type="text/javascript">
			function cargarSelect(valor)
			{
				if(valor==1)
				{
					document.getElementById("tipo").hidden=true;
					document.getElementById("buscador").hidden=false;
					document.getElementById("dia").hidden=true;
					document.getElementById("mes").hidden=true;
					document.getElementById("ano").hidden=true;
				}
				if(valor==2)
				{
					document.getElementById("tipo").hidden=true;
					document.getElementById("buscador").hidden=true;
					document.getElementById("dia").hidden=false;
					document.getElementById("mes").hidden=false;
					document.getElementById("ano").hidden=false;
				}
				if(valor==3)
				{
					document.getElementById("tipo").hidden=false;
					document.getElementById("buscador").hidden=true;
					document.getElementById("dia").hidden=true;
					document.getElementById("mes").hidden=true;
					document.getElementById("ano").hidden=true;
				}
		
			}
		</script>
	</head>
	<body>
		<h1 align="center" >Informe de Registros de Movimientos</h2>
		<div id="pestanas">
		<?php if($_SESSION['ID']!=4){?>
			<a href="Lista_Recetas.php">Recetas</a>
			<?php } ?>
			<?php if($_SESSION['ID']==4||$_SESSION['ID']==2||$_SESSION['ID']==6){?>
			<a href="VerListaPedidos.php">Pedidos</a>
			<?php } ?>
			<?php if($_SESSION['ID']!=4){?>
			<a href="Lista_Stock.php">Stock</a>
			<?php } ?>
			<a id="act" href="HistorialStock.php">Historial de Stock</a>
		</div>	
		<div id="caja" style="width:97%">		
			<div id="lista">		
				<?php
				if(count($this->movimientos)==0)
				{ ?>
				<p>No hay Movimientos</p>
				<?php 
				}
				else
				{
				?>
				<form action="HistorialStock.php" method="get" id="form_filtro">
					<span style="color:white">Informe de Registros de Movimientos</span><br/>
					<select name="filtro" id="filtro" onchange='cargarSelect(this.value);'>
						<option value="1">Receta o Ingrediente:</option>
						<option value="2">Fecha:</option>
						<option value="3">Tipo:</option>
					</select>
					<select name="tipo" id="tipo" hidden="true">
					<?php
						foreach($this->tipo as $t)	{
					?>
						<option value="<?= $t ['cod_tip_mov'] ?>"><?= $t ['detalle'] ?></option>
					<?php } ?>
					</select>
					<select name="dia" id="dia" hidden="true">
						<option value="0">-</option>
					<?php
						for($i=1;$i<32;$i++)	{
					?>
						<option value="<?= $i ?>"><?= $i ?></option>
					<?php } ?>
					</select>
					<select name="mes" id="mes" hidden="true">
						<option value="0">-</option>
					<?php
						for($i=1;$i<13;$i++)	{
					?>
						<option value="<?= $i ?>"><?= $i ?></option>
					<?php } ?>
					</select>
					<select name="ano" id="ano" hidden="true">
						<option value="0">-</option>
					<?php
						for($i=date("Y")-5;$i<date("Y")+5;$i++)	{
					?>
						<option value="<?= $i ?>"><?= $i ?></option>
					<?php } ?>
					</select>
					<input type="text" id="buscador"  maxlength="50" name="buscar"/>
					
					<input type="submit" class="boton" value="Buscar"/>
				</form>
				<table>
				<tr>
				<th>Fecha</th>
				<th>Tipo</th>
		
				<th>Ingrediente</th>
				<th>Seccion</th>
				<th>Contenedor</th>
				<th>Deposito</th>
				
				<th>Cant ant</th>
				<th>Cant act</th>
				
				<th>Anotacion</th>
				</tr>
				<?php
				
				foreach($this->movimientos as $m)
				{
				?>
				<tr>
				<td><?= date("d/m/Y H:i:s",strtotime($m ['fecha'])) ?></td>
				<td><?= $m ['detalle'] ?></td>
				
				
				<td>
				<?php
				foreach($this->linea_movimiento->getTodos_lin_mov($m['cod_mov']) as $l)
				{
				?>
				<?= $l ['matprima'] ?><br/>
				<?php
				}
				?>
				</td>
				
				<td>
				<?php
				foreach($this->linea_movimiento->getTodos_lin_mov($m['cod_mov']) as $l)
				{
				?>
				<?= $l ['sec'] ?><br/>
				<?php
				}
				?>
				</td>
				
				<td>
				<?php
				foreach($this->linea_movimiento->getTodos_lin_mov($m['cod_mov']) as $l)
				{
				?>
				<?= $l ['cont'] ?><br/>
				<?php
				}
				?>
				</td>
				
				<td>
				<?php
				foreach($this->linea_movimiento->getTodos_lin_mov($m['cod_mov']) as $l)
				{
				?>
				<?= $l ['dep'] ?><br/>
				<?php
				}
				?>
				</td>
				<td>
				<?php
				foreach($this->linea_movimiento->getTodos_lin_mov($m['cod_mov']) as $l)
				{
				?>
				<?= $l ['cant_ant'] ?><br/>
				<?php
				}
				?>
				</td>
				<td>
				<?php
				foreach($this->linea_movimiento->getTodos_lin_mov($m['cod_mov']) as $l)
				{
				?>
				<?= $l ['cant_act'] ?><br/>
				<?php
				}
				?>
				</td>
				<td><?= $m ['anotacion'] ?></td>
				</tr>
				<?php
				}
				?>
				</table>
				<?php } ?>
			</div>
			
		</div>
		<footer>
		<br/>
		SGSR &copy 2016 
		</footer>
	</body>
</html>