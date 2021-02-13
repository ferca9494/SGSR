<?php
require '../framework/fw.php';
require '../models/Movimientos.php';
require '../views/Historial.php';

if(isset($_SESSION['ID'])&&$_SESSION['ID']>=1&&$_SESSION['ID']<=3||$_SESSION['ID']==6)
{
	$m = new Movimientos;
	$vista = new Historial;

	$vista->tipo = $m->getTodoslostipos();
	$vista->linea_movimiento = $m;
	
	if(isset($_GET['filtro'])){
		if($_GET['filtro']==1&&isset($_GET['buscar']))
			$vista->movimientos = $m->Buscar_porreceta($_GET['buscar']);
		elseif($_GET['filtro']==2&&isset($_GET['dia'])&&isset($_GET['mes'])&&isset($_GET['ano']))
		{
			$fecha=$_GET['ano']."-".$_GET['mes']."-".$_GET['dia'];
			$vista->movimientos = $m->Buscar_porfecha($fecha);
		}
		elseif($_GET['filtro']==3&&isset($_GET['tipo']))
			$vista->movimientos = $m->Buscar_portipo($_GET['tipo']);
	}
	else
		$vista->movimientos = $m->getTodos_mov();
	
	
	$vista->Render();
}
else
	header("Location:login.php");


?>
