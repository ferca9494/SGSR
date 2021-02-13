<?php
require '../framework/fw.php';
require '../models/Recetas.php';
require '../views/Modificar_Ingrediente.php';

//wip
if(isset($_SESSION['ID'])&&($_SESSION['ID']==1||$_SESSION['ID']==6))
{
	if(isset($_GET['rec'])&&isset($_GET['reci'])){
		$rec = new Recetas;
		$vista = new Modificar_Ingrediente;
		$vista->este_ingre = $rec->getIngre($_GET['rec'],$_GET['reci']);
		$vista->ingre = $rec->Lista_MatPrimaypro();
		
		
		if(isset($_POST['ing'])&&isset($_POST['pre']))
		{
			$r = $rec->get_porid2($_GET['reci']);		
			$rec->ModIngrediente($_GET['rec'],$_GET['reci'],$_POST['ing'],$r['Cantidad_de_uso'],$r['Uso_extra']);
		}
		elseif(isset($_POST['ing'])&&isset($_POST['ing_cant'])&&isset($_POST['ing_upe']))
		{
			$rec->ModIngrediente($_GET['rec'],$_GET['reci'],$_POST['ing'],$_POST['ing_cant'],$_POST['ing_upe']);
		}
		$vista->Render();
	}
	else
		header("Location:Lista_Receta.php");
}
else
	header("Location:login.php");
?>