<?php
require '../framework/fw.php';
require '../models/Recetas.php';
require '../views/Admin_MatPrima.php';


if(isset($_SESSION['ID'])&&($_SESSION['ID']==1||$_SESSION['ID']==6))
{
	$vista = new Admin_MatPrima;
	$mp = new Recetas;
	
	if(!isset($_GET['buscarreceta'])&&!isset($_GET['filtro']))
	{
		$vista->matprimas = $mp->Lista_Matprima();
	}
	else
	{
		$vista->matprimas = $mp->Busca_MatPrima($_GET['buscarreceta']);
	}
	$vista->render();
}
else
	header("Location:login.php");

?>