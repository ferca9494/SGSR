<?php
require '../framework/fw.php';
require '../views/FormAltaPedidoPD.php';
require '../models/Pedidos.php';
require '../models/Recetas.php';
require '../models/Movimientos.php';
require '../models/Stock.php';
require '../models/Secciones.php';
//consultar a la tabla stock para obtener la cantidad en stock a retirar

$v = new FormAltaPedidoPD;
if(isset($_SESSION['ID'])&&($_SESSION['ID']==4||$_SESSION['ID']==6))
{
if(ISSET ($_POST['cod_receta'])) {
	if(!ISSET($_POST['cantidad'])) die('error');
	$e = new Pedidos;
	
	$v->entregado = $e -> entregarPD($_POST['cod_receta'], $_POST['cantidad']);

	$v -> render();
	exit;
}

$r = new recetas;
$recetas = $r -> getTodosPD();

$v -> recetas = $recetas;
$v -> render();
}
else
	header("Location:login.php");