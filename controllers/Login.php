<?php
require '../framework/fw.php';
require '../models/usuarios.php';
require '../views/Login.php';

$u = new usuarios;
$vista=new Login;
if(!isset($_SESSION['ID']))
{
	if(isset($_POST['nombre'])
	&&isset($_POST['pass'])
	)	
	{
		if($_POST['pass']==$u->getDatosByname($_POST['nombre'])['password'])
		{
			$_SESSION['ID'] = $u->getDatosByname($_POST['nombre'])['usuario_id'];
			if($_SESSION['ID']>=1&&$_SESSION['ID']<=3||$_SESSION['ID']==6)
				header("location:Lista_Recetas.php");
			elseif($_SESSION['ID']==4)
				header("location:VerListaPedidos.php");
			elseif($_SESSION['ID']==5)
				header("location:Ingreso_Stock.php?tipo=ing");
		}
		else
			$vista->render();
		
	}
	else
		$vista->render();
}
else
die("<p>ERROR: ya estas logueado o no tienes permisos</p></br><a href='logout.php'>Cerrar sesion</a>");
?>