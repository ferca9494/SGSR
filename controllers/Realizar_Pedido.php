<?php
require '../framework/fw.php';

require '../models/Pedidos.php';
require '../views/Realizar_Pedido.php';

require '../models/Recetas.php';
require '../models/Stock.php';
require '../models/Secciones.php';
require '../models/Tipo_conserva.php';
require '../models/Movimientos.php';

if(isset($_SESSION['ID'])&&($_SESSION['ID']==2||$_SESSION['ID']==4||$_SESSION['ID']==6))
{
	if(isset($_GET['id'])){
		$vista = new Realizar_Pedido;
		$p = new Pedidos;
		
		$vista->entregado=false;
		
		$vista->pedido = $p->get_porid($_GET['id']);				
		$vista->entregado = $p->entregarPedido($vista->pedido['cod_pedido'],$vista->pedido['cod_receta'],  $vista->pedido['cantidad']);	
		if(!$vista->entregado)
			$vista->render();	
		else
			header("location:VerListaPedidos.php");
	}		
	else
		header("location:VerListaPedidos.php");
}
else
	header("Location: Login.php");
?>