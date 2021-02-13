<?php
// Controllers / VerListaPedidos.php

require '../framework/fw.php';

require '../views/ListaPedidos.php';
require '../models/Pedidos.php';
require '../models/Stock.php';
require '../models/Movimientos.php';
require '../models/Recetas.php';


if(isset($_SESSION['ID'])&&($_SESSION['ID']==4||$_SESSION['ID']==2||$_SESSION['ID']==6))
{
$e = new Pedidos;
$todos = $e -> getTodos ();

$v = new ListaPedidos;
$v -> pedidos = $todos;

$v -> render ();
}
else
	header("Location:login.php");

