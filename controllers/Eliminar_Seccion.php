<?php
require '../framework/fw.php';
require '../models/Secciones.php';

if(isset($_SESSION['ID'])&&($_SESSION['ID']==1||$_SESSION['ID']==6))
{
	$s = new Secciones;
	if(isset($_GET['id']))
	{
		$s->Baja($_GET['id']);
	}
	Header("Location:Admin_Almacen.php");
}
else
	header("Location:login.php");
?>