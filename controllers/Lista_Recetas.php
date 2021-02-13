<?php
require '../framework/fw.php';
require '../models/Recetas.php';
require '../models/Tipo_receta.php';
require '../views/Lista_recetas.php';

if(isset($_SESSION['ID'])&&$_SESSION['ID']>=1&&$_SESSION['ID']<=3||$_SESSION['ID']==6)
{
	$rec = new Recetas;
	$tr = new Tipo_receta;
	$vista = new Lista_recetas;
	$vista->tipos = $tr->getTReceta();
	if(!isset($_GET['buscarreceta'])&&!isset($_GET['filtro']))
	{
		$vista->listareceta = $rec->Lista_Recetas();	
	}
	else
	{
	if($_GET['filtro']==1)
		$vista->listareceta = $rec->Busca_Receta($_GET['buscarreceta']);
	elseif($_GET['filtro']==2)
		$vista->listareceta = $rec->Busca_Receta_ing($_GET['buscarreceta']);
	elseif($_GET['filtro']==3)
		$vista->listareceta = $rec->Busca_Receta_portipo($_GET['filtro_tipo']);	
	}
	
		$vista->Render();
}
else
	header("Location:login.php");

?>
