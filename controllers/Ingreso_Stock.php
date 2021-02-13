<?php
require '../framework/fw.php';
require '../models/Stock.php';
require '../models/Secciones.php';
require '../models/Recetas.php';
require '../views/Ingreso_Stock.php';
require '../models/Movimientos.php';
if(isset($_SESSION['ID'])&&(($_SESSION['ID']==5&&$_GET['tipo']=="ing")||($_SESSION['ID']==4&&$_GET['tipo']=="dev")||$_SESSION['ID']==6))
{
	$vista = new Ingreso_Stock;
	$st = new Stock;
	$sec = new Secciones;
	$mp = new Recetas;
	$mov = new Movimientos;

	$vista->escaduco = false;
	$vista->mat = $mp;
	
	if(isset($_POST['mp']))
		$vista->seccion = $sec->get_por_tipo_conservacion($_POST['mp'],false);
	
	if(isset($_GET['tipo']))
	{
		if($_GET['tipo']=="ing")
		{
			$vista->mat_prima = $mp->Lista_Matprima();
		}elseif($_GET['tipo']=="dev")
		{
			$vista->mat_prima = $mp->Lista_Recetas_devolvibles();
		}
		
		if(
		isset($_POST['mp'])&&
		isset($_POST['cant'])
		)
		{	
			$vista->escaduco = $mp->es_caduco($_POST['mp']);
			
			if(
			isset($_POST['sec'])&&isset($_POST['disp'])&&
			(
			($vista->escaduco==true&&isset($_POST['dia_ven'])&&isset($_POST['mes_ven'])&&isset($_POST['ano_ven']))||
			$vista->escaduco==false
			)
			)
			{
				if(isset($_POST['anotacion']))
					$anot = $_POST['anotacion'];
				else
					$anot = "";
			
				if($_GET['tipo']=="ing"||$_GET['tipo']=="dev")
				{
					if($vista->escaduco==true)
						$fecha = $_POST['ano_ven']."-".$_POST['mes_ven']."-".$_POST['dia_ven'];
					else
						$fecha = null;

					if($_GET['tipo']=="ing")
					{
						
						$mov->altamovimiento1($_POST['mp'], $_POST['cant'],1, $anot, $_POST['sec'], $fecha,$_POST['disp']);
						
					}
					elseif($_GET['tipo']=="dev")
					{
						
						$mov->altamovimiento1($_POST['mp'], $_POST['cant'],7, $anot, $_POST['sec'], $fecha,$_POST['disp']);			
						
					}
				}	
			}
		}
			
		$vista->Render();
	}
	else
		header("Location:Lista_Recetas.php");
}
else
	header("Location:login.php");
?>
