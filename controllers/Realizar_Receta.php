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
	if(
		isset($_GET['id'])||
		(
			isset($_POST['cant'])&&
			isset($_POST['rec'])&&
			(
				isset($_GET['tipo'])&&
				($_GET['tipo']=="matpro"||$_GET['tipo']=="recdep"||$_GET['tipo']=="emple")
			)
		)
	)
	{
		$p = new Pedidos;
		$r = new Recetas;
		$c = new Tipo_conserva;
		$s = new Stock;
		$m = new Movimientos;
		$se = new Secciones;

		if(isset($_GET['id'])){
			$vista->pedido = $p->get_porid($_GET['id']);
			$vista->receta = $r->get_porid2($vista->pedido['cod_receta']);
			$vista->ingredientes = $r->ingredientes($vista->pedido['cod_receta']);
			$vista->sec = $se->get_por_tipo_conservacion($vista->pedido['cod_receta'],false);
			$vista->cant_en_stock = $s->stockSuficienteRec($vista->receta['cod_receta'], $vista->pedido['cantidad']);
		}elseif(isset($_GET['tipo'])&&isset($_POST['rec'])&&isset($_POST['cant'])){
			
			$vista->receta = $r->get_porid2($_POST['rec']);
			$vista->ingredientes = $r->ingredientes($_POST['rec']);
			$vista->sec = $se->get_por_tipo_conservacion($_POST['rec'],false);
			$vista->cant_en_stock = $s->stockSuficienteRec($_POST['rec'], $_POST['cant']);
			
		}
		$vista->entregado=false;
		$vista->cat_rec = $c->getTodo();
		
		
		if($vista->receta['tipo_receta']==4)
		{
			//if(isset($_POST['aceptar']))
			
			
			$vista->entregado = $p->entregarPedido($vista->pedido['cod_pedido'],$vista->receta['cod_receta'],  $vista->pedido['cantidad']);
			
			if(!$vista->entregado)
				$vista->render();	
			else
				header("location:VerListaPedidos.php");
		}elseif($vista->receta['tipo_receta']==5)
		{
			//if(isset($_POST['aceptar']))
			
			
			$vista->entregado = $p->entregarEMPLE($_POST['rec'],  $_POST['cant']);
			
			if(!$vista->entregado)
				$vista->render();	
			else
				header("location:VerListaPedidos.php");
		}		
		elseif($vista->receta['tipo_receta']==7||$vista->receta['tipo_receta']==6)
		{
			if(
				isset($_POST['aceptar'])&&
				isset($_POST['disp'])&&
				isset($_POST['seccion'])&&
				isset($_POST['dia_ven'])&&
				isset($_POST['mes_ven'])&&
				isset($_POST['ano_ven'])
			)
			{
				if(isset($_POST['anotacion']))$anot = $_POST['anotacion'];else $anot = "";
				
				$fecha = $_POST['ano_ven']."-".$_POST['mes_ven']."-".$_POST['dia_ven'];
				
				if(isset($_POST['rec'])&&isset($_POST['cant']))
					$vista->entregado = $p->realizarRecetayguardar($_POST['rec'], $_POST['cant'],$_POST['seccion'],$fecha,$anot,$_POST['disp']);
				else
					$vista->entregado = $p->realizarPedidoyguardar($vista->pedido['cod_pedido'],$vista->receta['cod_receta'],  $vista->pedido['cantidad'],$_POST['seccion'],$fecha,$anot,$_POST['disp']);
				
				$vista->render();
			}	
			else
			{
				$vista->render();
			}
		}
		else
		{		
				$vista->render();
		}
	}
	elseif(
		isset($_GET['tipo'])&&
		($_GET['tipo']=="matpro"||$_GET['tipo']=="recdep"||$_GET['tipo']=="emple")&&
		(!isset($_POST['cant'])&&!isset($_POST['rec']))
	)
	{

		$r = new Recetas;
		if($_GET['tipo']=="matpro")
			$vista->recetas = $r->Lista_Recetas_matpro();
		elseif($_GET['tipo']=="recdep")
			$vista->recetas = $r->Lista_Recetas_recdep();
		elseif($_GET['tipo']=="emple")
			$vista->recetas = $r->Lista_Recetas_Empl();
			
		$vista->render();
	}
}
else
	header("Location: Login.php");
?>