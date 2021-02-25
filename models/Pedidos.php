<?php
//models/Pedidos.php

//hecho enteramente por gari
class Pedidos extends Model {

	public function getTodos(){
		$this -> db -> query("SELECT p.cod_pedido as 'cod_pedido', p.cod_receta as 'cod_receta', r.detalle as 'detalle', p.cantidad as 'cantidad', p.esta_entregado as 'esta_entregado', pidio_extra
								FROM pedidos p
								
								LEFT JOIN recetas r on p.cod_receta = r.cod_receta
								where p.esta_entregado = 'n'");
		return $this -> db -> fetchAll ();
	}
	
	public function get_porid($id)
	{
		$this -> db ->query ("SELECT *
									FROM pedidos
									WHERE cod_pedido = ".$id);
		return $this->db->fetch();
	}	
	
	public function existePedido ($id){
			if(!ctype_digit($id)) die('error 1');
			$this -> db ->query ("SELECT cod_pedido
									FROM pedidos
									WHERE cod_pedido = ".$id);
			if($this-> db -> numRows()!=1) return false;
			else return true;
	}

	public function entregarPedido($cod_pedido,$cod_receta, $cantidad)
	{
		//cod_receta
		if(!ctype_digit($cod_receta)) die('cod_receta no es un digito');
		$aux = new Recetas;
		if(!$aux -> existeRecPedCom ($cod_receta)) die('no existe la receta');
		//cantidad
		if(!ctype_digit($cantidad)) die('cantidad no es un digito');
		if($cantidad < 1) die('ingrese cantidad mayor a 0');
		//cod_pedido
		if (!ctype_digit($cod_pedido)) die('el pedido no es digito');
		$aux = new Pedidos;
		if (!$aux -> existePedido ($cod_pedido))die('no existe el pedido');
		
		$auxmov = new Movimientos;
		$alta=$auxmov -> altaMovimiento ($cod_receta,$cantidad,4,'',$cod_pedido);
		
		if(!$alta)return false;
		
		$this -> db -> query ("UPDATE pedidos
								SET esta_entregado = 's'
								WHERE cod_pedido =". $cod_pedido." LIMIT 1");
								
		return true;
								
	}

	public function entregarEMPLE($cod_receta, $cantidad){
		//cod_receta
		if(!ctype_digit($cod_receta)) die('cod_receta no es un digito');
		$aux = new Recetas;
		if(!$aux -> existeRecEmpl ($cod_receta)) die('no existe la Materia Prima');
		
		//cantidad
		if(!ctype_digit($cantidad)) die('cantidad no es un digito');
		if($cantidad < 1) die('ingrese cantidad mayor a 0');
		
		$auxmov = new Movimientos;
		$alta=$auxmov->altaMovimiento ($cod_receta,$cantidad,5,'',null);
		if(!$alta)return false;
		return true;
	}

	public function entregarMP($cod_receta, $cantidad){
		
		//wip
		//cod_receta
		if(!ctype_digit($cod_receta)) die('cod_receta no es un digito');
		$aux = new Recetas;
		if(!$aux -> existeRecMP ($cod_receta)) die('no existe la Materia Prima');
		//cantidad
		if(!ctype_digit($cantidad)) die('cantidad no es un digito');
		if($cantidad < 1) die('ingrese cantidad mayor a 0');
		
		$auxmov = new Movimientos;
		$alta=$auxmov -> altaMovimiento ($cod_receta,$cantidad,9,'',null);	
		if(!$alta)return false;
		return true;
	}
	public function entregarPD($cod_receta, $cantidad){
		
		//wip
		//cod_receta
		if(!ctype_digit($cod_receta)) die('cod_receta no es un digito');
		$aux = new Recetas;
		if(!$aux -> existeRecPD ($cod_receta)) die('no existe la Materia Prima');
		//cantidad
		if(!ctype_digit($cantidad)) die('cantidad no es un digito');
		if($cantidad < 1) die('ingrese cantidad mayor a 0');
		
		$auxmov = new Movimientos;
		$alta=$auxmov -> altaMovimiento ($cod_receta,$cantidad,13,'',null);	
		if(!$alta)return false;
		return true;
	}
	
	public function realizarRecetayguardar($cod_receta, $cantidad,$seccion,$fecha_ven,$anotacion,$disp)
	{
		//cod_receta
		if(!ctype_digit($cod_receta)) die('cod_receta no es un digito');
		$aux = new Recetas;
		//if(!$aux -> existeRecPedCom ($cod_receta)) die('no existe la receta');
		//cantidad
		if(!ctype_digit($cantidad)) die('cantidad no es un digito');
		if($cantidad < 1) die('ingrese cantidad mayor a 0');
			
		$rec =	Recetas::get_porid2($cod_receta);
		if($rec['tipo_receta']==6)
			$tipo_mov=11;
		elseif($rec['tipo_receta']==7)
			$tipo_mov=10;
			
		$auxmov = new Movimientos;	
		$alta=$auxmov -> altaMovimiento1 ($cod_receta, $cantidad,$tipo_mov, $anotacion, $seccion, $fecha_ven,$disp);
		if(!$alta)return false;
		return true;
	}
	
	public function verificar_si_se_puede_realizar($rec)
	{
		
		if(!ctype_digit($rec)) 
		die('cod_receta no es un digito');
		
		$ingre = Recetas::ingredientes($rec);
		
		foreach($ingre as $i)
		{
			if(Stock::stockAntiguo($i['ingrediente'])==null) return false;	
		}
		return true;
	}
	
	public function altaPedido ( $cod_receta, $cantidad,$extra) {
		//cod_receta
		if(!ctype_digit($cod_receta)) die('cod_receta no es un digito');
		$aux = new Recetas;
		if(!$aux -> existeRecPedCom ($cod_receta)) die('no existe la receta');
		//cantidad
		if(!ctype_digit($cantidad)) die('cantidad no es un digito');
		if($cantidad < 1) die('ingrese cantidad mayor a 0');
		
		if($extra != 'n'&&$extra != 's') die('error:extra');
		
		$this -> db -> query ("INSERT INTO pedidos
								(cod_receta, cantidad,pidio_extra) VALUE
								('$cod_receta', $cantidad ,'$extra') ");
	}
	
	public function bajaPedido ($id) {
		
		$this -> db -> query ("delete from pedidos
								where cod_pedido = $id ");
	}
	
}