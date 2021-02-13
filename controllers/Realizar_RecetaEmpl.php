<?php
require '../framework/fw.php';
require '../models/Recetas.php';
require '../models/Pedidos.php';
require '../models/Stock.php';
require '../models/Secciones.php';
require '../models/Tipo_conserva.php';
require '../views/Realizar_Receta.php';
require '../models/Movimientos.php';

if(isset($_SESSION['ID'])&&($_SESSION['ID']==2||$_SESSION['ID']==4||$_SESSION['ID']==6)){
		
	$vista = new Realizar_Receta;
	$p = new Pedidos;
	$r = new Recetas;
	$c = new Tipo_conserva;
	$s = new Stock;
	$m = new Movimientos;
	$se = new Secciones;
	$vista->entregado=false;
	$vista->recetas = $r->Lista_Recetas_Empl();
	if(isset($_POST['rec'])&&isset($_POST['cant'])){

		$vista->entregado = $p->entregarEMPLE($_POST['rec'],  $_POST['cant']);		

	}

	$vista->render();	
}
else
	header("Location: Login.php");
?>