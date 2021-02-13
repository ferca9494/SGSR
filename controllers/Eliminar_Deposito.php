<?php
require '../framework/fw.php';
require '../models/Depositos.php';

if(isset($_SESSION['ID'])&&($_SESSION['ID']==1||$_SESSION['ID']==6))
{
	$d = new Depositos;
	if(isset($_GET['id']))
	{
		$d->Baja($_GET['id']);
	}
	Header("Location:Admin_Almacen.php");
}
else
	header("Location:login.php");
?>