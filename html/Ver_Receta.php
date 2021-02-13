<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"/>
		<title>SGSR - <?=$this->receta['detalle']?></title>
		<link rel="stylesheet" type="text/css" href="estilos.css" >
		<style>
		td{
		text-align:center
		}
		#caja{
		margin-left:13%;
		}
		</style>
	</head>
	<body>
		<h1 align="center" >Informe de Receta</h1>
	
		
		<div id="caja">
			
			<div id="titulo">
				<span style="margin-left:0%;text-align:left">
				<input type="button" class="boton" value="Volver" onclick="history.go(-1)"/>
				</span>
				
				<span >
					<?=$this->receta['nombre']?>
				</span>
				
				<?php if($_SESSION['ID']==1||$_SESSION['ID']==6){ //22?>
				<span style="margin-left:22%;">
				<a class="modificar" href="Modificar_Receta.php?id=<?=$_GET['id']?>">Modificar</a> - 
				<a class="eliminar" href="Eliminar_Receta.php?id=<?=$_GET['id']?>">Eliminar</a>
				</span>
				<?php } ?>
				
			</div>
			<ul>
				<li><b>Tipo:</b> <?=$this->receta['tipo']?></li>
			</ul>			
			<?php if(($this->receta['tipo_receta']>=1&&$this->receta['tipo_receta']<=3)||$this->receta['tipo_receta']==6) { ?>
			<ul>
			<li><b>Cantidad de uso:</b> <?=$this->receta['Cantidad_de_uso']?></li>
			<li><b>Uso por Extra:</b> <?=$this->receta['Uso_extra']?></li>
			<li><b>Es caduco?:</b> <?php if($this->receta['Es_caduco']=='s')echo"Si";else echo"No";?></li>
			</ul>
			<?php } ?>
			<?php if($this->conserva!=null) { ?>
			<ul>
			<li><b>Conserva:</b> <?=$this->conserva['detalle']?></li>
			</ul>
			<?php } ?>
			
			<?php if($this->receta['Anotacion']!=""){?>
			<div id="anotacion">Anotacion: <br/><?=$this->receta['Anotacion']?></div>
			<?php } ?>
			<br/>
			<?php if($this->receta['tipo_receta']>=4&&$this->receta['tipo_receta']<=7) { ?>
			<div id="lista">	
				<table>
				<tr>
				<th>Detalle</th>
				<th>Cantidad a usar</th>
				<th>Uso por Extra</th>
				</tr>
				<?php
				foreach($this->ingredientes as $i)
				{
				?>
				<tr>
				<td><a href="Ver_Receta.php?id=<?=$i['id']?>"><?php echo$i['det']; ?></a></td>
				<td><?= $i['cpu'] ?></td>
				<td><?= $i['upe'] ?></td>
				</tr>
				<?php
				}
				?>
				</table>
			</div>
			<?php } ?>		
		</div>
		<footer>
		<br/>
		SGSR &copy 2016 
		</footer>
	</body>
</html>