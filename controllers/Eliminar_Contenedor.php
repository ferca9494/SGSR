<?php
require '../framework/fw.php';
require '../models/Contenedores.php';

if(isset($_SESSION['ID'])&&($_SESSION['ID']==1||$_SESSION['ID']==6))
{
	$c = new Contenedores;
	if(isset($_GET['id']))
	{
		$c->Baja($_GET['id']);
	}
	Header("Location:Admin_Almacen.php");
}
else
	header("Location:login.php");
?>