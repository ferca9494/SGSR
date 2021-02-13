<?php
require '../framework/fw.php';
require '../models/Secciones.php';
require '../models/Contenedores.php';
require '../models/Tipo_Conserva.php';
require '../views/Registro_Seccion.php';


if(isset($_SESSION['ID'])&&($_SESSION['ID']==1||$_SESSION['ID']==6))
{
	$sec = new Secciones;
	$con = new Contenedores;
	$tc = new Tipo_Conserva;
	$vista = new Registro_Seccion;
	$vista->contenedores = $con->getTodos();
	$vista->tiposC = $tc->getTodo();
	

	if(isset($_POST['cant2']))
	{
		for($i=1;$i<=$_POST['cant2'];$i++){
			if(isset($_POST['detalle_'.$i])&&isset($_POST['tc_'.$i]))
			{
				if(isset($_GET['con']))
				{
					$sec->Registro($_POST['detalle_'.$i],$_GET['con'],$_POST['tc_'.$i]);
				}
				elseif(isset($_POST['con2']))
				{
					$sec->Registro($_POST['detalle_'.$i],$_POST['con2'],$_POST['tc_'.$i]);
				}
			}
		}
	}
	$vista->Render();
}
else
	header("Location:login.php");
?>