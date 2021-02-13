<?php

require '../framework/fw.php';
require '../models/Recetas.php';
require '../models/Tipo_Conserva.php';
require '../views/Ver_Receta.php';

if(isset($_SESSION['ID'])&&$_SESSION['ID']!=5)
{
if(isset($_GET['id']))
{
	$vista = new Ver_Receta;
	$rec =  new Recetas;
	$tc = new Tipo_Conserva;
	$vista->receta = $rec->get_porid2($_GET['id']);
	$vista->conserva = null;
	if(($vista->receta['tipo_receta']>=1&&$vista->receta['tipo_receta']<=3)||$vista->receta['tipo_receta']==6||$vista->receta['tipo_receta']==7) {
		$vista->conserva =  $tc->getTCreceta($_GET['id']);
	}
	$vista->ingredientes = $rec->get_matporreceta($_GET['id']);
	$vista->render();
}
}
else
	header("Location:login.php");
?>