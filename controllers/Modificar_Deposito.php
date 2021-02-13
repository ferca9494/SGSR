<?php
require '../framework/fw.php';
require '../models/Depositos.php';
require '../views/Modificar_Deposito.php';

//wip
if(isset($_SESSION['ID'])&&($_SESSION['ID']==1||$_SESSION['ID']==6))
{
	if(isset($_GET['id']))
	{
		$dep = new Depositos;
		$vista = new Modificar_Deposito;
		$vista->deposito = $dep->get_porid($_GET['id']);
		if(isset($_POST['detalle']))
		{
			$dep->Modificar($_GET['id'],$_POST['detalle']);
		}
		$vista->Render();
	}
}
else
	header("Location:login.php");
?>