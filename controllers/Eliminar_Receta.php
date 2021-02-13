<?php
require '../framework/fw.php';
require '../models/Recetas.php';

if(isset($_SESSION['ID'])&&($_SESSION['ID']==1||$_SESSION['ID']==6))
{
	$r = new Recetas;
	if(isset($_GET['id']))
		$r->Baja($_GET['id']);
		
	if(isset($_GET['tipo'])&&$_GET['tipo']=="rec")
		Header("Location:Lista_Recetas.php");
	elseif(isset($_GET['tipo'])&&$_GET['tipo']=="mp")
		Header("Location:Admin_MatPrima.php");
	else
		Header("Location:Lista_Recetas.php");
}
else
	header("Location:login.php");
?>