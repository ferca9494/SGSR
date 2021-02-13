<?php
require '../framework/fw.php';
require '../models/Depositos.php';
require '../models/Contenedores.php';
require '../models/Secciones.php';
require '../models/Stock.php';
require '../models/Recetas.php';
require '../views/Admin_Almacen.php';
if(isset($_SESSION['ID'])&&($_SESSION['ID']==1||$_SESSION['ID']==6))
{
	$dep = new Depositos;
	$vista = new Admin_Almacen;

	$vista->listadeposito = $dep->getTodos();
	$vista->listacontenedor = new Contenedores;
	$vista->listaseccion = new Secciones;
	$vista->listastock = new Stock;
	$vista->Render();
}
else
	header("Location:login.php");


?>
