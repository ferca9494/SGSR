<?php
require '../framework/fw.php';
require '../views/FormAltaPedido.php';
require '../models/Pedidos.php';
require '../models/Recetas.php';
require '../models/Movimientos.php';
require '../models/Stock.php';
require '../models/Secciones.php';
$v = new FormAltaPedido;
if(isset($_SESSION['ID'])&&($_SESSION['ID']==4||$_SESSION['ID']==6))
{
if(ISSET ($_POST['cod_receta'])||ISSET ($_GET['id'])) {
	if(!ISSET($_POST['cantidad'])) die('error');
	$e = new Pedidos;
	
	if(isset($_POST['extra']))
		$ex='s';
	else
		$ex='n';
	
	if(isset($_POST['cod_receta']))
		$e -> altaPedido($_POST['cod_receta'], $_POST['cantidad'],$ex);
	elseif($_GET['id'])
		$e -> altaPedido($_GET['id'], $_POST['cantidad'],$ex);
	
	$v -> render();
	exit;
}

	$r = new recetas;
	$recetas = $r -> getTodosPedCom();

	$v -> recetas = $recetas;
	$v -> render();
}
else
	header("Location:login.php");
