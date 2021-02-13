<?php
require '../framework/fw.php';
require '../models/Recetas.php';
require '../models/Tipo_conserva.php';
require '../models/Tipo_receta.php';
require '../views/Registro_Receta.php';
require '../views/Registro_Ingrediente.php';

if(isset($_SESSION['ID'])&&($_SESSION['ID']==1||$_SESSION['ID']==2||$_SESSION['ID']==6))
{
	if(isset($_GET['tipo'])&&($_GET['tipo']=="mp"||$_GET['tipo']=="rec"))
	{
		$vistaR = new Registro_Receta;
		$vistaI = new Registro_Ingrediente;
		$r= new Recetas;
		$tr= new Tipo_receta;
		$cr= new Tipo_conserva;

		if($_GET['tipo']=="mp")
			$vistaR->tipo_mat = $tr-> getTMatPrima();
		elseif($_GET['tipo']=="rec")
			$vistaR->tipo_mat = $tr-> getTReceta();
		
		$vistaR->cat_mat = $cr->getTodo();
		$vistaI->ingre = $r->Lista_MatPrimaypro();
		
		$vistaR->listo=false;
		
		if(isset($_POST['rec_detalle'])&&isset($_POST['rec_tipo']))
		{	
			if(
				isset($_POST['mp_cantuso'])&&isset($_POST['mp_uextra'])&&isset($_POST['mp_cat'])&&
				$_POST['rec_tipo']>=1&&$_POST['rec_tipo']<=3
			)
			{
				if(isset($_POST['mp_cad']))$_POST['mp_cad']='s';else $_POST['mp_cad']='n';
				if(isset($_POST['rec_anot']))$anot=$_POST['rec_anot'];else $anot="";
				
				$r->Registro($_POST['rec_detalle'],$_POST['mp_cad'],$_POST['rec_anot'],$_POST['mp_cantuso'],$_POST['mp_uextra'],$_POST['rec_tipo'],$_POST['mp_cat']);
				$vistaR->listo=true;
				$vistaR->Render();
			}
			elseif(
				isset($_POST['cantingre'])&&
				$_POST['rec_tipo']==7||$_POST['rec_tipo']==4||$_POST['rec_tipo']==5||$_POST['rec_tipo']==6
			)
			{
				if(isset($_POST['mp_cad']))$_POST['mp_cad']='s';else $_POST['mp_cad']='n';
				$vistaI->cantingre = $_POST['cantingre'];
				$vistaI->Render();
			}
		}
		elseif(isset($_POST['cantingre2']))
		{
			if(
				isset($_POST['mp_cantuso2'])&&isset($_POST['mp_uextra2'])&&isset($_POST['mp_cat2'])&&isset($_POST['mp_cad2'])&&
				$_POST['rec_tipo2']==6
			)
			{
				if(isset($_POST['rec_anot2']))$anot=$_POST['rec_anot2'];else $anot="";				
				$receta = $r->Registro($_POST['rec_detalle2'],$_POST['mp_cad2'],$_POST['rec_anot2'],$_POST['mp_cantuso2'],$_POST['mp_uextra2'],$_POST['rec_tipo2'],$_POST['mp_cat2']);
			}
			elseif(
				isset($_POST['mp_cat2'])&&isset($_POST['cantingre2'])&&isset($_POST['mp_cad2'])&&
				$_POST['rec_tipo2']==7
			)
			{
				if(isset($_POST['rec_anot2']))$anot=$_POST['rec_anot2'];else $anot="";				
				$receta = $r->Registro($_POST['rec_detalle2'],$_POST['mp_cad2'],$_POST['rec_anot2'],null,null,$_POST['rec_tipo2'],$_POST['mp_cat2']);
			}
			elseif(
				$_POST['rec_tipo2']==4||$_POST['rec_tipo2']==5
			)
			{				
				if(isset($_POST['rec_anot2']))$anot=$_POST['rec_anot2'];else $anot="";
				$receta = $r->Registro($_POST['rec_detalle2'],null,$_POST['rec_anot2'],null,null,$_POST['rec_tipo2'],null);
			}

			$cont=0;
			for($i=1;$i<=$_POST['cantingre2'];$i++)
			{
				if(isset($_POST['ing'.$i])&&((isset($_POST['ing_cant'.$i])&&isset($_POST['ing_upe'.$i])) || isset($_POST['ing_pre'.$i])))
				{				
					if(isset($_POST['ing_pre'.$i]))
					{
						$rec = $r->get_porid2($_POST['ing'.$i]);				
						$r->Registro_Ingrediente(strval($receta),$_POST['ing'.$i],$rec['Cantidad_de_uso'],$rec['Uso_extra']);
					}
					else
					{
						
						$r->Registro_Ingrediente(strval($receta),$_POST['ing'.$i],$_POST['ing_cant'.$i],$_POST['ing_upe'.$i]);
					}		
					$cont++;
				}
			}
			if($cont==$i-1&&$cont!=0)
			{
				$vistaR->listo=true;
				$vistaR->Render();
			}
			else
				$vistaR->Render();
		}
		else				
			$vistaR->Render();

	}
	else
		header("Location:Lista_Recetas.php");
}
else
	header("Location:login.php");
?>
