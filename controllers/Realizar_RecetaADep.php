<?php
require '../framework/fw.php';
require '../models/Recetas.php';
require '../models/Pedidos.php';
require '../models/Stock.php';
require '../models/Secciones.php';
require '../models/Tipo_conserva.php';
require '../views/Realizar_RecetayGuardar.php';
require '../models/Movimientos.php';

if(isset($_SESSION['ID'])&&($_SESSION['ID']==2||$_SESSION['ID']==4||$_SESSION['ID']==6)){
		
	$vista = new Realizar_RecetayGuardar;
	$p = new Pedidos;
	$r = new Recetas;
	$c = new Tipo_conserva;
	$s = new Stock;
	$m = new Movimientos;
	$se = new Secciones;
	$vista->entregado=false;
	$vista->recetas = $r->Lista_Recetas_recdep();
	$vista->titulo = "Receta de Plato a Depositar";
	if(isset($_POST['rec'])&&isset($_POST['cant'])){
		
		$vista->sec = $se->get_por_tipo_conservacion($_POST['rec'],false);
		$vista->rec = $r->get_porid2($_POST['rec']);
		$vista->verificar = $p->verificar_si_se_puede_realizar($_POST['rec']);//wip
		if(isset($_POST['seccion'])&&isset($_POST['disp'])){

			if(isset($_POST['anotacion']))$anot = $_POST['anotacion'];else $anot = "";

			if(isset($_POST['ano_ven'])&&isset($_POST['mes_ven'])&&isset($_POST['dia_ven'])){
			$fecha = $_POST['ano_ven']."-".$_POST['mes_ven']."-".$_POST['dia_ven'];
			}else $fecha="0000-00-00";
			
			$p->realizarRecetayguardar($_POST['rec'], $_POST['cant'],$_POST['seccion'],$fecha,$anot,$_POST['disp']);
			
		}
	}

	$vista->render();	
}
else
	header("Location: Login.php");
?>