<?php
require '../framework/fw.php';
require '../models/Stock.php';
require '../models/Secciones.php';
require '../models/Recetas.php';
require '../views/Modificar_Stock.php';
require '../models/Movimientos.php';

//wip
if(isset($_SESSION['ID'])&&($_SESSION['ID']==1||$_SESSION['ID']==2||$_SESSION['ID']==6))
{
	$vista = new Modificar_Stock;
	$st = new Stock;
	$sec = new Secciones;
	$mp = new Recetas;
	$mov = new Movimientos;
	
	if(isset($_GET['id']))
	{
		if(
		(isset($_POST['tipo'])&&isset($_POST['cantidad1'])&&isset($_POST['anotacion']))||
		(isset($_POST['tipo'])&&isset($_POST['cantidad2'])&&isset($_POST['anotacion']))||
		(isset($_POST['tipo'])&&isset($_POST['cantidad3'])&&isset($_POST['anotacion'])&&isset($_POST['sec'])&&isset($_POST['disp']))
		)
		{	
			
			if($_POST['tipo']==1)
				$mov->altaMovimiento2($_GET['id'],$_POST['cantidad1'],3,null,$_POST['anotacion'],null);
			elseif($_POST['tipo']==2)		
				$mov->altaMovimiento2($_GET['id'],$_POST['cantidad2'],2,null,$_POST['anotacion'],null);	
			elseif($_POST['tipo']==3&&($_SESSION['ID']==2||$_SESSION['ID']==6))			
				$mov->altaMovimiento2($_GET['id'],$_POST['cantidad3'],6,$_POST['sec'],$_POST['anotacion'],$_POST['disp']);		

		
			$vista->render();
		}
		else
		{	
			$vista->stock = $st->get_porid($_GET['id']);

			$vista->seccion = $sec->get_por_tipo_conservacion($vista->stock['cod_receta'],false);
			$vista->render();
		}
	}
	else
		header("Location:Lista_Stock.php");
}
else
	header("Location:login.php");

?>