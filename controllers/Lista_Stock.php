<?php
require '../framework/fw.php';
require '../models/Depositos.php';
require '../models/Contenedores.php';
require '../models/Secciones.php';
require '../models/Stock.php';
require '../models/Recetas.php';
require '../views/Lista_Stock.php';

if(isset($_SESSION['ID'])&&$_SESSION['ID']!=5&&$_SESSION['ID']!=4)
{
	$dep = new Depositos;
	$vista = new Lista_Stock;
	$vista->listadepositoespecial = $dep->getEspecial();
	$vista->listacontenedor = new Contenedores;
	$vista->listaseccion = new Secciones;
	$vista->listastock = new Stock;
	if(isset($_GET['filtro']))
	{	
		if(isset($_GET['buscarreceta'])&&$_GET['filtro']==1)
			$vista->buscastock = $vista->listastock->Buscar_poring($_GET['buscarreceta']);	
		elseif(!isset($_GET['buscarreceta'])&&$_GET['filtro']==2)
			$vista->buscastock = $vista->listastock->Buscar_casicaducado_poring();
		elseif(!isset($_GET['buscarreceta'])&&$_GET['filtro']==3)
			$vista->buscastock = $vista->listastock->Buscar_caducado_poring();
	}
	else
		$vista->listadeposito =  $dep->getnoEspecial();

	$vista->Render();
}
else
	header("Location:login.php");


?>
