<?php	
$lde = $this->listadepositoespecial;
$ld = $this->listadeposito;
$lc = $this->listacontenedor;
$ls = $this->listaseccion;
$lst= $this->listastock;

function resaltarvencimiento($fecha_ven){

	$a = date("Y-m-d");
	$enunasemana = strtotime('- 7 day',strtotime($fecha_ven));
	$semana = date("Y-m-d",$enunasemana);
	var_dump($enunasemana);
	
	if ($a<$fecha_ven&&$a>=$semana)
	{
		echo"class='casivencido'";
	}
	elseif($fecha_ven<=$a)
	{
		echo"class='vencido'";
	}

}

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"/>
		<title>SGSR - Informe de Stock</title>
		<link rel="stylesheet" type="text/css" href="estilos.css" >
		<style>
		td{text-align:center}
		#buscador{
		width:89.5%;
		}
		</style>
		<script>
			function esdepdeldia(valor)
			{
				if(valor == 5)				
					document.getElementById("minibuscador").disabled=true;
				else
					document.getElementById("minibuscador").disabled=false;
			}
			function button(spnum){
				var button = document.getElementById('spoiler_' + spnum);
				var contenido = document.getElementById('contenido_' + spnum);
				if(contenido.hidden == true){
				  contenido.hidden = false;}
				else if(contenido.hidden == false){
				  contenido.hidden = true;}
			}
			function cargarSelect(valor){
				if(valor==3||valor==2)
				{
					document.getElementById("minibuscador").disabled=true;
				}
				else
				{
					document.getElementById("minibuscador").disabled=false;
				}
			}
		</script>
	</head>
	<body>
		<h1 align="center" >Informe de Stock</h2>
		<div id="pestanas">
		<?php if($_SESSION['ID']!=4){?>
			<a href="Lista_Recetas.php">Recetas</a>
			<?php } ?>
			<?php if($_SESSION['ID']==4||$_SESSION['ID']==2||$_SESSION['ID']==6){?>
			<a href="VerListaPedidos.php">Pedidos</a>
			<?php } ?>
			<a  id="act" href="Lista_Stock.php">Stock</a>
			<?php if($_SESSION['ID']==1||$_SESSION['ID']==3||$_SESSION['ID']==6){?>
			<a href="HistorialStock.php">Historial de Stock</a>
			<?php  } ?>
		</div>
		<div id="caja">
			<div id="lista">
				<div id="titulo">
					<span style="color:white">Informe de Stock</span>
					<?php if(isset($_GET['filtro'])){?>
						<?php if($_GET['filtro']==1){?>
							<span style="color:white">(busqueda)</span>
						<?php }elseif($_GET['filtro']==2){?>
							<span style="color:white">(por fecha de vencimiento)</span>
						<?php }elseif($_GET['filtro']==3){?>
							<span style="color:white">(vencidos)</span>
						<?php } ?>
					<?php } ?>
				</div>
				
				<?php
				if(!isset($_GET['buscarreceta'])&&!isset($_GET['filtro']))
				{	
				?>
					<p><b>Deposito <?= $lde['detalle'] ?></b></p>				
					
					<?php	
					if($lc->getdedeposito($lde['cod_deposito'])==null)
					echo " - No hay Contenedores -";
					else{					
					foreach($lc->getdedeposito($lde['cod_deposito']) as $con)
					{ 				
					?>			
					<p> - Contenedor <?= $con['detalle'] ?></p>
						<?php
						if($ls->getdecont($con['cod_contenedor'])==null)
						echo " - No hay Secciones -";
					    else{
						?>
						<table>
						<tr>
							<th>Seccion</th>
							<th>Estado</th>
							<th>Fecha de Ingreso</th>
							<th>Objeto</th>
							<th>Cantidad</th>		
							<th>Fecha de Vencimiento</th>
							<?php if($_SESSION['ID']==1||$_SESSION['ID']==2||$_SESSION['ID']==6) { ?>
							<th>Opciones</th>
							<?php } ?>
						</tr>
						<?php
						foreach($ls->getdecont($con['cod_contenedor']) as $sec)
						{
						?>
						<tr>
						<td><b><?= $sec['det'] ?></b><br/><i><?= $sec['tipo'] ?></i></td>
						<td><b><?= $sec['disponibilidad'] ?></b></td>
							<?php
							if($lst->get_porseccion($sec['cod_seccion'])==null)
							{ ?>
							<td>-</td>
							<td>-</td>
							<td>-</td>					
							<td>-</td>
							<?php if($_SESSION['ID']==1||$_SESSION['ID']==2||$_SESSION['ID']==6) { ?>
							<td>-</td>
							<?php } ?>
							<?php
							}else{
							?>					
							<td >
							<?php
							foreach($lst->get_porseccion($sec['cod_seccion']) as $stk){echo date("d/m/Y",strtotime($stk['fecha'])) ;echo"</br>";}
							echo "</td><td>";
							foreach($lst->get_porseccion($sec['cod_seccion']) as $stk){ ?>
							<a href="Ver_receta.php?id=<?=$stk['id_rec']?>"><b <?php if($stk['caduco']=='s')resaltarvencimiento($stk['fecha_ven']); ?>><?=$stk['detalle_rec']?></b></a><br/>
							<?php
							}
							echo "</td><td>";
							foreach($lst->get_porseccion($sec['cod_seccion']) as $stk){echo $stk['stock'] ;echo"</br>";}
							echo "</td><td>";
							foreach($lst->get_porseccion($sec['cod_seccion']) as $stk){ if($stk['caduco']=='s')echo date("d/m/Y",strtotime($stk['fecha_ven']));else echo"-"; echo"</br>";}
							echo "</td>";
							if($_SESSION['ID']==1||$_SESSION['ID']==2||$_SESSION['ID']==6) { 
							echo"<td>";
							foreach($lst->get_porseccion($sec['cod_seccion']) as $stk){ ?><a class="modificar" href="Modificar_stock.php?id=<?=$stk['id']?>">Mod</a></br><?php }
							?>
							</td> 
							<?php } ?>
							<?php
							}
							?>
						</tr>
						<?php
						}
						?>
						</table>
						<?php
						}
						?>
					
					<?php
					}}
					?>
				<div class="separador"></div>
				
				<?php
				if($ld==null)
					echo " - No hay Depositos -";
				else{		
					foreach($ld as $dep)
					{
				?>		
				
				<p>
				<button  id="spoiler_<?=$dep['cod_deposito']?>" class="boton"   onclick='button(<?=$dep['cod_deposito']?>)'>+</button>
				<b>Deposito <?= $dep['detalle'] ?></b></p>	
				<div id="contenido_<?=$dep['cod_deposito']?>" hidden="false">		
				
					<?php	
					
					if($lc->getdedeposito($dep['cod_deposito'])==null)
					echo " - No hay Contenedores -";
					else{					
					foreach($lc->getdedeposito($dep['cod_deposito']) as $con)
					{ 				
					?>			
					<p> - Contenedor <?= $con['detalle'] ?></p>
						<?php
						if($ls->getdecont($con['cod_contenedor'])==null)
						echo " - No hay Secciones -";
					    else{
						?>
						<table>
						<tr>
							<th>Seccion</th>
							<th>Estado</th>
							<th>Fecha de Ingreso</th>
							<th>Objeto</th>
							<th>Cantidad</th>		
							<th>Fecha de Vencimiento</th>
							<?php if($_SESSION['ID']==1||$_SESSION['ID']==2||$_SESSION['ID']==6) { ?>
							<th>Opciones</th>
							<?php } ?>
						</tr>
						<?php
						foreach($ls->getdecont($con['cod_contenedor']) as $sec)
						{
						?>
						<tr>
						<td><b><?= $sec['det'] ?></b><br/><i><?= $sec['tipo'] ?></i></td>
						<td><b><?= $sec['disponibilidad'] ?></b></td>
							<?php
							if($lst->get_porseccion($sec['cod_seccion'])==null)
							{ ?>
							<td>-</td>
							<td>-</td>
							<td>-</td>					
							<td>-</td>
							<?php if($_SESSION['ID']==1||$_SESSION['ID']==2||$_SESSION['ID']==6) { ?>
							<td>-</td>
							<?php } ?>
							<?php
							}else{
							?>					
							<td >
							<?php
							foreach($lst->get_porseccion($sec['cod_seccion']) as $stk){echo date("d/m/Y",strtotime($stk['fecha'])) ;echo"</br>";}
							echo "</td><td>";
							foreach($lst->get_porseccion($sec['cod_seccion']) as $stk){ ?>
							<a href="Ver_receta.php?id=<?=$stk['id_rec']?>"><b <?=resaltarvencimiento($stk['fecha_ven'])?>><?=$stk['detalle_rec']?></b></a><br/>
							<?php
							}
							echo "</td><td>";
							foreach($lst->get_porseccion($sec['cod_seccion']) as $stk){echo $stk['stock'] ;echo"</br>";}
							echo "</td><td>";
							foreach($lst->get_porseccion($sec['cod_seccion']) as $stk){echo date("d/m/Y",strtotime($stk['fecha_ven'])) ;echo"</br>";}
							echo "</td>";
							if($_SESSION['ID']==1||$_SESSION['ID']==2||$_SESSION['ID']==6) { 
							echo"<td>";
							foreach($lst->get_porseccion($sec['cod_seccion']) as $stk){ ?><a class="modificar"  href="Modificar_stock.php?id=<?=$stk['id']?>">Mod</a></br><?php }
							?>
							</td> 
							<?php } ?>
							<?php
							}
							?>
						</tr>
						<?php
						}
						?>
						</table>
						<?php
						}
						?>
					<?php
					}}
					?>
					</div>
				<?php
				}
				?>
				
				<?php
				}
				?>
				
				<?php }else{ ?>
				
				<table>
					<tr>
						<th>Fecha de ingreso</th>
						<th>Deposito</th>
						<th>Contenedor</th>
						<th>Seccion</th>
						<th>Objeto</th>
						<th>Cantidad</th>
						<th>Fecha de vencimiento</th>
					</tr>
					<?php foreach($this->buscastock as $s){ ?>
					<tr>
						<td><?=date("d/m/Y",strtotime($s['fecha_ingreso'])) ?></td>
						<td><?=$s['d']?></td>
						<td><?=$s['c']?></td>
						<td><?=$s['s']?></td>
						<td><?=$s['r']?></td>
						<td><?=$s['cantidad']?></td>
						<td><?=date("d/m/Y",strtotime($s['fecha_vencimiento'])) ?></td>
					</tr>
					<?php } ?>
				</table>
				<?php
				}
				?>
			</div>
		</div>
		<div class="menu">
			<p>Buscar</p>	
			<form action="" method="get" align="center">		
					<input type="text" id="minibuscador" maxlength="50" name="buscarreceta"/>
					<input type="submit" class="boton" value="Buscar"/>			
					<br/>			
					<select name="filtro" id="filtro"  style="margin:10px" onchange='cargarSelect(this.value);'>
						<option value="1">Ingrediente:</option>
						<option value="2">Ingredientes a punto de vencer</option>
						<option value="3">Ingredientes vencidos</option>
					</select>
			</form>
		</div>
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