<?php
require '../framework/fw.php';
require '../models/Secciones.php';
require '../models/Contenedores.php';
require '../models/Tipo_Conserva.php';
require '../views/Modificar_Seccion.php';


if(isset($_SESSION['ID'])&&($_SESSION['ID']==1||$_SESSION['ID']==6))
{
	$sec = new Secciones;

	$tc = new Tipo_Conserva;
	$vista = new Modificar_Seccion;

	$vista->tiposC = $tc->getTodo();
	$vista->seccion =  $sec->get_porid($_GET['id']);
	if(isset($_POST['detalle'])&&isset($_POST['tc']))
	{
		$sec->Modificar($_GET['id'],$_POST['detalle'],$_POST['tc']);
	}	
	
	$vista->Render();
}
else
	header("Location:login.php");
?>