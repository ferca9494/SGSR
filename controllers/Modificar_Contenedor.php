<?php
require '../framework/fw.php';
require '../models/Contenedores.php';
require '../models/Depositos.php';
require '../views/Modificar_Contenedor.php';


if(isset($_SESSION['ID'])&&($_SESSION['ID']==1||$_SESSION['ID']==6))
{
	if(isset($_GET['id']))
	{
	$dep = new Depositos;
	$con = new Contenedores;
	$vista = new Modificar_Contenedor;
	$vista->depositos = $dep->getTodos();
	$vista->contenedor = $con->get_porid($_GET['id']);
	
	if(isset($_POST['detalle'])&&isset($_POST['dep']))
	{
		$con->Modificar($_GET['id'],$_POST['detalle'],$_POST['dep']);		
	}

	$vista->Render();
	}
}
else
	header("Location:login.php");
?>