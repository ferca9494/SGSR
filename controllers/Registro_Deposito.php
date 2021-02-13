<?php
require '../framework/fw.php';
require '../models/Depositos.php';
require '../views/Registro_Deposito.php';

//wip
if(isset($_SESSION['ID'])&&($_SESSION['ID']==1||$_SESSION['ID']==6))
{
	$dep = new Depositos;
	$vista = new Registro_Deposito;

	if(isset($_POST['detalle']))
	{
		$dep->Registro($_POST['detalle']);
	}
	
	$vista->Render();
}
else
	header("Location:login.php");
?>