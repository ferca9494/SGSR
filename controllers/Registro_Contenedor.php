<?php
require '../framework/fw.php';
require '../models/Contenedores.php';
require '../models/Depositos.php';
require '../views/Registro_Contenedor.php';


if(isset($_SESSION['ID'])&&($_SESSION['ID']==1||$_SESSION['ID']==6))
{
	$dep = new Depositos;
	$con = new Contenedores;
	$vista = new Registro_Contenedor;
	$vista->depositos = $dep->getTodos();
	
	if(isset($_POST['detalle']))
	{
		if(isset($_GET['dep']))
		{
			$vista->este_cont = $con->Registro($_POST['detalle'],$_GET['dep']);
		}
		elseif(isset($_POST['dep']))
		{
			$vista->este_cont = $con->Registro($_POST['detalle'],$_POST['dep']);
		}
	}

	$vista->Render();
}
else
	header("Location:login.php");
?>