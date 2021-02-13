<?php
require '../framework/fw.php';
require '../models/Recetas.php';
require '../models/Tipo_conserva.php';
require '../models/Tipo_receta.php';
require '../views/Modificar_Receta.php';
require '../views/Registro_Ingrediente.php';

if(isset($_SESSION['ID'])&&($_SESSION['ID']==1||$_SESSION['ID']==2||$_SESSION['ID']==6))
{
	$vistaR = new Modificar_Receta;
	$r= new Recetas;
	$tr= new Tipo_receta;
	$cr= new Tipo_conserva;

	$vistaR->cat_mat = $cr->getTodo();
	
	$vistaR->listo=false;
	if(isset($_GET['id']))
	{
		$tipo = $tr->get_porid($_GET['id'])['cod_tipo_receta'];
	
		if($tipo==4||$tipo==5)
			$vistaR->esta_receta = $r->get_porid2($_GET['id']);
		else
			$vistaR->esta_receta = $r->get_porid($_GET['id']);
		
		$vistaR->ing_esta_receta = $r->get_matporreceta($_GET['id']);
		
		if($vistaR->esta_receta['tipo_receta']==1||$vistaR->esta_receta['tipo_receta']==2||$vistaR->esta_receta['tipo_receta']==3)
			$vistaR->tipo_mat = $tr->getTMatPrima();
		elseif($vistaR->esta_receta['tipo_receta']==4||$vistaR->esta_receta['tipo_receta']==5)
			$vistaR->tipo_mat = $tr->getTRecetaCyE();
		else
			$vistaR->tipo_mat = null;
			
		if(isset($_POST['rec_detalle'])&&isset($_POST['rec_tipo'])&&isset($_POST['rec_anot']))
		{	
				if(
				isset($_POST['mp_cantuso'])&&isset($_POST['mp_uextra'])&&isset($_POST['mp_cat'])&&
				(($_POST['rec_tipo']>=1&&$_POST['rec_tipo']<=3)||$_POST['rec_tipo']==6)
				)
				{
					if(isset($_POST['mp_cad']))$_POST['mp_cad']='s';else $_POST['mp_cad']='n';
					$r->Modificar($_GET['id'],$_POST['rec_detalle'],$_POST['mp_cad'],$_POST['rec_anot'],$_POST['mp_cantuso'],$_POST['mp_uextra'],$_POST['rec_tipo'],$_POST['mp_cat']);
					$vistaR->listo=true;
					$vistaR->Render();
				}
				elseif(
				isset($_POST['mp_cat'])&&
				$_POST['rec_tipo']==7
				)
				{
					if(isset($_POST['mp_cad']))$_POST['mp_cad']='s';else $_POST['mp_cad']='n';
					$r->Modificar($_GET['id'],$_POST['rec_detalle'],$_POST['mp_cad'],$_POST['rec_anot'],null,null,$_POST['rec_tipo'],$_POST['mp_cat']);
					
					$vistaR->listo=true;
					$vistaR->Render();
					
				}
				elseif(
				$_POST['rec_tipo']==4||$_POST['rec_tipo']==5
				)
				{
					$r->Modificar($_GET['id'],$_POST['rec_detalle'],null,$_POST['rec_anot'],null,null,$_POST['rec_tipo'],null);
					
					$vistaR->listo=true;
					$vistaR->Render();		
				}
		}
		else
			$vistaR->Render();

	}
	else
		header("Location:admin_matprima.php");
}
else
	header("Location:login.php");
?>
