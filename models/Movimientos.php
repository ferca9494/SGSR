<?php 

//models/Movimientos.php
//hecho enteramente por gari

class Movimientos extends Model{

	public function getTodoslostipos(){
		$this -> db -> query("SELECT tm.cod_tip_mov as 'cod_tip_mov', tm.detalle AS detalle
								FROM `tipo_movimiento` tm");
		return $this -> db -> fetchAll ();
	}

	public function getTodos_mov(){
		$this -> db -> query("SELECT *
								FROM registro_de_movimientos rm , tipo_movimiento tm
								where rm.tipo_mov = tm.cod_tip_mov
								order by rm.fecha desc");	
		return $this -> db -> fetchAll ();
	}
	public function getTodos_lin_mov($mov){
		$this -> db -> query("SELECT r.detalle as matprima , se.detalle as sec , lr.cantidad_anterior as cant_ant , lr.cantidad_actual as cant_act,co.detalle as cont,de.detalle as dep
								FROM linea_registro lr,stock s,recetas r,secciones se,contenedores co,depositos de
								where lr.cod_stock = s.cod_stock
								and lr.cod_mov = '$mov'
								and s.cod_receta = r.cod_receta
								and s.cod_seccion = se.cod_seccion
								and se.cod_contenedor = co.cod_contenedor
								and co.cod_deposito = de.cod_deposito");	
		return $this -> db -> fetchAll ();
	}
	public function existeMovimiento ($id){
			if(!ctype_digit($id)) die('error 1');
			$this -> db ->query ("SELECT cod_tip_mov
									FROM tipo_movimiento
									WHERE cod_tip_mov = ".$id);
			if($this-> db -> numRows()!=1) return false;
			else return true;
	}
	public function Buscar_porfecha($fecha)
	{
		$this -> db -> query("SELECT *
								FROM registro_de_movimientos rm , tipo_movimiento tm
								where rm.tipo_mov = tm.cod_tip_mov
								and date(rm.fecha) = '$fecha'
								order by rm.fecha desc");	
		return $this -> db -> fetchAll ();
	}
	public function Buscar_porreceta($rec)
	{
		if(strlen($rec)<1)die("error1: detalle de receta invalido");
		if(!substr($rec,0,20))die("error2: detalle de receta invalido");
		$this->db->escape($rec);
		
		$this -> db -> query("SELECT *
								FROM registro_de_movimientos rm , tipo_movimiento tm , linea_registro lr , recetas r , stock s
								where rm.tipo_mov = tm.cod_tip_mov
								and lr.cod_mov = rm.cod_mov
								and lr.cod_stock = s.cod_stock
								and s.cod_receta = r.cod_receta
								and r.detalle like '%$rec%'
								order by fecha desc");	
		return $this -> db -> fetchAll ();
	}
	public function Buscar_portipo($tipo)
	{	
		if(!ctype_digit($tipo)) die('error 1');
			$this -> db ->query ("SELECT cod_tip_mov
									FROM tipo_movimiento
									WHERE cod_tip_mov = ".$tipo);
	
		$this -> db -> query("SELECT *
								FROM registro_de_movimientos rm , tipo_movimiento tm
								where rm.tipo_mov = tm.cod_tip_mov
								and rm.tipo_mov = $tipo
								order by fecha desc");	
		return $this -> db -> fetchAll ();
	}
//------------------------------------------------------------------------------------------------------------------------
	public function altaMovimiento1 ($cod_receta, $cantidad,$tipo_mov, $anotacion, $cod_seccion, $fecha_vencimiento,$disp){
	
		if($tipo_mov<0)die('error');
		if(!ctype_digit($cod_receta))die("Error 1:receta invalida");
		$this->db->query(
		"select *
		from recetas
		where cod_receta = '$cod_receta'
		and habilitado = 's'"
		);
		if($this->db->numRows()==0)die("Error 2:receta con este tipo inexistente");
		if(!ctype_digit($cantidad)) die('error');
		if($cantidad<0)die('error');
		
		if(!ctype_digit($cod_seccion))die("Error 1:receta invalida");
		$this->db->query(
		"select *
		from secciones
		where cod_seccion = '$cod_seccion'
		and habilitado = 's'"
		);
		if($this->db->numRows()==0)die("Error 2:seccion inexistente");
		if($disp!="Lleno"&&$disp!="Con espacio"&&$disp!="Vacio")die("error: disponibilidad invalida");
		
		
		//Ingreso o devolucion
		if ($tipo_mov == 1||$tipo_mov == 7) {	
			/*
			INGRESO DE STOCK
			*/
			Stock::Registro($cantidad ,$fecha_vencimiento, $cod_receta, $cod_seccion,$disp);
			$cod_stock = mysqli_insert_id($this -> db -> getcn());
			
			/*
			REGISTRAR MOVIMIENTO
			*/
			$this -> db ->query ("INSERT INTO `registro_de_movimientos`
									(fecha ,tipo_mov, anotacion)
								VALUES
									(NOW(), $tipo_mov, '$anotacion')");
		
			$cod_mov =  mysqli_insert_id($this-> db->getcn());
			
			/*
			REGISTRAR LINEA MOVIMIENTO
			*/
			Movimientos::altaLinea($cod_stock, 0, $cantidad, $cod_mov);
		}
		//realizacion de receta adepositar o realizacion de mat proc 
		if ($tipo_mov == 10||$tipo_mov == 11) {		
			
			/*
			INGRESO DE STOCK
			*/		
			Stock::Registro($cantidad,$fecha_vencimiento,$cod_receta,$cod_seccion,$disp);
			$np = mysqli_insert_id($this-> db->getcn());
			
			/*
			REGISTRAR MOVIMIENTO
			*/
			$this -> db ->query ("INSERT INTO `registro_de_movimientos`
									(fecha ,tipo_mov)
								VALUES
									(NOW(), $tipo_mov)");
			$cod_mov =  mysqli_insert_id($this-> db->getcn());
			
			/*
			REGISTRAR LINEA MOVIMIENTO
			*/			
			Movimientos :: altaLinea($np,0, $cantidad, $cod_mov);
			
			$rec = new Recetas;
			$rec = $rec -> ingredientes($cod_receta);
			
			foreach ($rec as $key => $value) {
			
				$cant_uso = $value['cantidad'];
				$cod_ingrediente = $value['ingrediente'];
				$aux = new Stock;
				
				/*
				VERIFICAR EXISTENCIA DE INGREDIENTE EN STOCK
				*/
				$cod_stock = $aux->stockAntiguo($cod_ingrediente);
				if($cod_stock==null){
					Movimientos::baja($cod_mov);
					return false;
				}
				
				$cant_stock = $cod_stock['cantidad'];
				$cod_stock = $cod_stock['cod_stock'];	
				$cant_total = $cantidad * $cant_uso;
				
			
				while ( $cant_total > 0) {
				
					if($cant_stock > $cant_total){
						/*
						DISMINUIR STOCK
						*/
						$aux ->  aumentarStock($cod_stock,-$cant_total);
						
						/*
						CAMBIAR DISPONIBILIDAD SI ES QUE HAY O NO STOCK
						*/
						$secant = Stock::get_porstock($cod_stock)['cod_seccion'];	
						if(Stock::haystock($secant)==true)
							Secciones::cambiarDisp($secant,"Con espacio");
						else
							Secciones::cambiarDisp($secant,"Vacio");
						
						/*
						REGISTRAR LINEA MOVIMIENTO
						*/	
						Movimientos::altaLinea($cod_stock,$cant_stock, $cant_stock - $cant_total, $cod_mov);
						
						$cant_total = 0;
					}
					
					if ($cant_stock <= $cant_total) {
						/*
						DISMINUIR STOCK
						*/					
						$aux ->  aumentarStock($cod_stock,-$cant_stock);
			
						/*
						CAMBIAR DISPONIBILIDAD SI ES QUE HAY O NO STOCK
						*/						
						$secant = Stock::get_porstock($cod_stock)['cod_seccion'];	
						if(Stock::haystock($secant)==true)
							Secciones::cambiarDisp($secant,"Con espacio");
						else
							Secciones::cambiarDisp($secant,"Vacio");
							
						/*
						REGISTRAR LINEA MOVIMIENTO
						*/							
						Movimientos::altaLinea($cod_stock, $cant_stock, $cant_stock - $cant_stock, $cod_mov);

						/*
						DESABILITAR STOCK
						*/		
						$aux -> desabilitarStock($cod_stock);
						
						$cant_total = $cant_total - $cant_stock;
					}
					
					$cod_stock = $aux->stockAntiguo($cod_ingrediente);
					if($cod_stock==null){
						Movimientos::baja($cod_mov);
						return false;
					}
					
					$cant_stock = $cod_stock['cantidad'];
				
					$cod_stock = $cod_stock['cod_stock'];
				}
			}
			
			
		}	
	
		return true;
	}
//------------------------------------------------------------------------------------------------------------------------
	public function altaMovimiento2 ($cod_stock , $cantidad,$tipo_mov, $seccion,$anotacion,$disp){
		
		if($tipo_mov<0)die('error2');
		if(!ctype_digit($cod_stock))die("Error 1:stock invalido");
		$this->db->query(
		"select *
		from stock
		where cod_stock = '$cod_stock'
		and habilitado = 's'
		and cantidad > 0"
		);
		if($this->db->numRows()==0)die("Error 2:stock inexistente");
		if(!ctype_digit($cantidad)) die('error');
		if($cantidad<0)die('error');
	
		
		//correccion
		if ($tipo_mov == 2) {

			/*
			CAMBIAR DISPONIBILIDAD SI ES QUE HAY O NO STOCK
			*/		
			$secant = Stock::get_porstock($cod_stock)['cod_seccion'];	
			if(Stock::haystock($secant)==true)
				Secciones::cambiarDisp($secant,"Con espacio");
			else
				Secciones::cambiarDisp($secant,"Vacio");		
			
			/*
			CAMBIAR CANTIDAD EN STOCK
			*/
			$cant_ant = Stock::cantAntStock($cod_stock);
			Stock::cambiarCantidadStock($cod_stock,$cantidad);
				
			/*
			REGISTRAR MOVIMIENTO
			*/
			$this -> db ->query ("
			INSERT INTO `registro_de_movimientos`(fecha ,tipo_mov, anotacion)
			VALUES(NOW(), '$tipo_mov', '$anotacion')"
			);
			$cod_mov =  mysqli_insert_id($this-> db->getcn());
			
			/*
			REGISTRAR LINEA MOVIMIENTO
			*/
			Movimientos::altaLinea($cod_stock, $cant_ant['cantidad'], $cantidad, $cod_mov);
		}
		//perdida
		if ($tipo_mov == 3) 
		{
		
			/*
			CAMBIAR DISPONIBILIDAD SI ES QUE HAY O NO STOCK
			*/							
			$secant = Stock::get_porstock($cod_stock)['cod_seccion'];	
			if(Stock::haystock($secant)==true)
				Secciones::cambiarDisp($secant,"Con espacio");
			else
				Secciones::cambiarDisp($secant,"Vacio");
				
			/*
			CAMBIAR CANTIDAD EN STOCK
			*/			
			$cant_ant = Stock::cantAntStock($cod_stock);
			if($cant_ant['cantidad']-$cantidad<0)
				Stock::aumentarStock($cod_stock,-$cant_ant['cantidad']);
			else
				Stock::aumentarStock($cod_stock,-$cantidad);
				
			/*
			REGISTRAR MOVIMIENTO
			*/				
			$this -> db ->query ("
			INSERT INTO `registro_de_movimientos` (fecha ,tipo_mov, anotacion)
			VALUES (NOW(), $tipo_mov, '$anotacion')"
			);
			$cod_mov =  mysqli_insert_id($this-> db->getcn());
			
			/*
			REGISTRAR LINEA MOVIMIENTO
			*/
			Movimientos::altaLinea($cod_stock, $cant_ant['cantidad'], $cant_ant['cantidad']-$cantidad, $cod_mov);
		}
		//traslado de stock
		if ($tipo_mov == 6) {
			
			if(!ctype_digit($seccion))die("Error 1:seccion invalida");
			$this->db->query(
				"select *
				from secciones
				where cod_seccion = '$seccion'
				and habilitado = 's'"
			);
			if($this->db->numRows()==0)die("Error 2:seccion inexistente");
			if($disp!="Lleno"&&$disp!="Con espacio"&&$disp!="Vacio")die("error: disponibilidad invalida");
			
			$stk = Stock::get_porid($cod_stock);
			if($stk['cantidad']==$cantidad)
			{
			
				/*
				DISMINUIR STOCK
				*/					
				Stock::aumentarStock($cod_stock,-$stk['cantidad']);
					
				/*
				CAMBIAR DISPONIBILIDAD SI ES QUE HAY O NO STOCK
				*/		
				$secant = Stock::get_porstock($cod_stock)['cod_seccion'];	
				if(Stock::haystock($secant)==true)
					Secciones::cambiarDisp($secant,"Con espacio");
				else
					Secciones::cambiarDisp($secant,"Vacio");
				
				/*
				INGRESO DE STOCK
				*/	
				Stock::Registro($stk['cantidad'],$stk['fecha_vencimiento'],$stk['cod_receta'],$seccion,$disp);				
				$cod_nuevo_stock =  mysqli_insert_id($this-> db->getcn());
				/*
				REGISTRAR MOVIMIENTO
				*/				
				$this -> db ->query ("
				INSERT INTO `registro_de_movimientos` (fecha ,tipo_mov, anotacion)
				VALUES (NOW(), $tipo_mov, '$anotacion')"
				);
				$cod_mov =  mysqli_insert_id($this-> db->getcn());
				
				/*
				REGISTRAR LINEA MOVIMIENTO
				*/		
				Movimientos::altaLinea($cod_nuevo_stock, 0, $cantidad, $cod_mov);				
				Movimientos::altaLinea($cod_stock, $stk['cantidad'], 0, $cod_mov);		
				
			}
			elseif($stk['cantidad']>$cantidad)
			{
				/*
				DISMINUIR STOCK
				*/					
				Stock::aumentarStock($cod_stock,-$cantidad);
			
				/*
				CAMBIAR DISPONIBILIDAD SI ES QUE HAY O NO STOCK
				*/						
				$secant = Stock::get_porstock($cod_stock)['cod_seccion'];	
				if(Stock::haystock($secant)==true)
				Secciones::cambiarDisp($secant,"Con espacio");
				else
				Secciones::cambiarDisp($secant,"Vacio");
	
				/*
				INGRESO DE STOCK
				*/					
				Stock::Registro($cantidad,$stk['fecha_vencimiento'],$stk['cod_receta'],$seccion,$disp);
				
				/*
				REGISTRAR MOVIMIENTO
				*/					
				$this -> db ->query ("
				INSERT INTO `registro_de_movimientos` (fecha ,tipo_mov, anotacion)
				VALUES (NOW(), $tipo_mov, '$anotacion')"
				);
				$cod_mov =  mysqli_insert_id($this-> db->getcn());
				
				/*
				REGISTRAR LINEA MOVIMIENTO
				*/					
				Movimientos::altaLinea($cod_stock, $stk['cantidad'], $stk['cantidad']-$cantidad, $cod_mov);
				Movimientos::altaLinea($cod_stock, 0, $cantidad, $cod_mov);
				
			}
			elseif($stk['cantidad']<$cantidad)
			{
				die("ERROR: no hay suficiente cantidad en stock");
			}
		}
	
		return true;
	}
//------------------------------------------------------------------------------------------------------------------------
	public function altaMovimiento ($cod_receta, $cantidad,$tipo_mov, $anotacion,$cod_pedido){

		if($tipo_mov<0)die('error');
		if(!ctype_digit($cod_receta))die("Error 1:receta invalida");
		$this->db->query(
		"select *
		from recetas
		where cod_receta = '$cod_receta'
		and habilitado = 's'"
		);
		if($this->db->numRows()==0)die("Error 2:receta con este tipo inexistente");
		if(!ctype_digit($cantidad)) die('error');
		if($cantidad<0)die('error');	
		

	//Realizacion de receta com 
		if ($tipo_mov == 4) {
			
	
			//cod_pedido
			if (!ctype_digit($cod_pedido)) die('el pedido no es digito');
			$aux = new Pedidos;
			if (!$aux -> existePedido ($cod_pedido))die('no existe el pedido');
			$ped = $aux->get_porid($cod_pedido);
			
			/*
			INGRESO DE STOCK FANTASMA(DESABILITADO)
			*/
			Stock::Registro_fantasma($cantidad, '0000-00-00', $cod_receta,"Vacio");
			$np = mysqli_insert_id($this-> db->getcn());		
			Stock:: desabilitarStock($np);

			/*
			REGISTRAR MOVIMIENTO 
			*/			
			$this -> db ->query ("INSERT INTO `registro_de_movimientos`
									(fecha ,tipo_mov, anotacion)
								VALUES
									(NOW(), $tipo_mov, '$anotacion')");		
			$cod_mov =  mysqli_insert_id($this-> db->getcn());
			
			/*
			REGISTRAR LINEA MOVIMIENTO 
			*/					
			Movimientos :: altaLinea($np,0, $cantidad, $cod_mov);
		
			$rec = new Recetas;
			$rec = $rec -> ingredientes($cod_receta);
			
			foreach ($rec as $key => $value) {
			
				$cant_uso = $value['cantidad'];
				$upe = $value['uso_por_extra'];
				$cod_ingrediente = $value['ingrediente'];
				$aux = new Stock;

				/*
				VERIFICAR EXISTENCIA DE INGREDIENTE EN STOCK
				*/				
				$cod_stock = $aux->stockAntiguo($cod_ingrediente);
				
				if($cod_stock==null){
					Movimientos::baja($cod_mov);
					return false;
				}
			
				$cant_stock = $cod_stock['cantidad'];			
				$cod_stock = $cod_stock['cod_stock'];	
				if($ped['pidio_extra']=="n")
					$cant_total = $cantidad * $cant_uso;
				elseif($ped['pidio_extra']=="s")				
					$cant_total = $cantidad * ($cant_uso + $upe);

				while ( $cant_total > 0) {
				
					/*
					VERIFICAR EXISTENCIA DE INGREDIENTE EN STOCK
					*/					
					$cod_stock = $aux->stockAntiguo($cod_ingrediente);
					
					if($cod_stock==null){
						Movimientos::baja($cod_mov);
						return false;
					}
					
					$cant_stock = $cod_stock['cantidad'];			
					$cod_stock = $cod_stock['cod_stock'];
				
					if($cant_stock > $cant_total){
						/*
						DISMINUIR STOCK
						*/						
						$aux -> aumentarStock($cod_stock,-$cant_total);
						
						/*
						CAMBIAR DISPONIBILIDAD SI ES QUE HAY O NO STOCK
						*/								
						$secant = Stock::get_porstock($cod_stock)['cod_seccion'];	
						if(Stock::haystock($secant)==true)
						Secciones::cambiarDisp($secant,"Con espacio");
						else
						Secciones::cambiarDisp($secant,"Vacio");
						
						/*
						REGISTRAR LINEA MOVIMIENTO 
						*/							
						Movimientos::altaLinea($cod_stock,$cant_stock, $cant_stock - $cant_total, $cod_mov);///
						$cant_total = 0;
					}
					if ($cant_stock <= $cant_total) {
					
						/*
						DISMINUIR STOCK
						*/	
						$aux -> aumentarStock($cod_stock,-$cant_stock);
						
						/*
						CAMBIAR DISPONIBILIDAD SI ES QUE HAY O NO STOCK
						*/								
						$secant = Stock::get_porstock($cod_stock)['cod_seccion'];	
							if(Stock::haystock($secant)==true)
							Secciones::cambiarDisp($secant,"Con espacio");
						else
						Secciones::cambiarDisp($secant,"Vacio");
						
						/*
						REGISTRAR LINEA MOVIMIENTO 
						*/						
						Movimientos::altaLinea($cod_stock, $cant_stock, $cant_stock - $cant_stock, $cod_mov);///
						
						/*
						DESABILITAR STOCK
						*/	
						$aux -> desabilitarStock($cod_stock);
						$cant_total = $cant_total - $cant_stock;
					}
					
					
				}
			}
		

		}

		
		//Realizacion de receta empl
		if ($tipo_mov == 5) {

			/*
			INGRESO DE STOCK FANTASMA(DESABILITADO)
			*/
			Stock::Registro_fantasma($cantidad, '0000-00-00', $cod_receta,"Vacio");
			$np = mysqli_insert_id($this-> db->getcn());		
			Stock:: desabilitarStock($np);

			/*
			REGISTRAR MOVIMIENTO 
			*/			
			$this -> db ->query ("INSERT INTO `registro_de_movimientos`
									(fecha ,tipo_mov, anotacion)
								VALUES
									(NOW(), $tipo_mov, '$anotacion')");		
			$cod_mov =  mysqli_insert_id($this-> db->getcn());
			
			/*
			REGISTRAR LINEA MOVIMIENTO 
			*/					
			Movimientos :: altaLinea($np,0, $cantidad, $cod_mov);
		
			$rec = new Recetas;
			$rec = $rec -> ingredientes($cod_receta);
			
			foreach ($rec as $key => $value) {
			
				$cant_uso = $value['cantidad'];
				$cod_ingrediente = $value['ingrediente'];
				$aux = new Stock;

				/*
				VERIFICAR EXISTENCIA DE INGREDIENTE EN STOCK
				*/				
				$cod_stock = $aux->stockAntiguo($cod_ingrediente);
				
				if($cod_stock==null){
					Movimientos::baja($cod_mov);
					return false;
				}
			
				$cant_stock = $cod_stock['cantidad'];			
				$cod_stock = $cod_stock['cod_stock'];	

				while ( $cant_total > 0) {
				
					/*
					VERIFICAR EXISTENCIA DE INGREDIENTE EN STOCK
					*/					
					$cod_stock = $aux->stockAntiguo($cod_ingrediente);
					
					if($cod_stock==null){
						Movimientos::baja($cod_mov);
						return false;
					}
					
					$cant_stock = $cod_stock['cantidad'];			
					$cod_stock = $cod_stock['cod_stock'];
				
					if($cant_stock > $cant_total){
						/*
						DISMINUIR STOCK
						*/						
						$aux -> aumentarStock($cod_stock,-$cant_total);
						
						/*
						CAMBIAR DISPONIBILIDAD SI ES QUE HAY O NO STOCK
						*/								
						$secant = Stock::get_porstock($cod_stock)['cod_seccion'];	
						if(Stock::haystock($secant)==true)
						Secciones::cambiarDisp($secant,"Con espacio");
						else
						Secciones::cambiarDisp($secant,"Vacio");
						
						/*
						REGISTRAR LINEA MOVIMIENTO 
						*/							
						Movimientos::altaLinea($cod_stock,$cant_stock, $cant_stock - $cant_total, $cod_mov);///
						$cant_total = 0;
					}
					if ($cant_stock <= $cant_total) {
					
						/*
						DISMINUIR STOCK
						*/	
						$aux -> aumentarStock($cod_stock,-$cant_stock);
						
						/*
						CAMBIAR DISPONIBILIDAD SI ES QUE HAY O NO STOCK
						*/								
						$secant = Stock::get_porstock($cod_stock)['cod_seccion'];	
							if(Stock::haystock($secant)==true)
							Secciones::cambiarDisp($secant,"Con espacio");
						else
						Secciones::cambiarDisp($secant,"Vacio");
						
						/*
						REGISTRAR LINEA MOVIMIENTO 
						*/						
						Movimientos::altaLinea($cod_stock, $cant_stock, $cant_stock - $cant_stock, $cod_mov);///
						
						/*
						DESABILITAR STOCK
						*/	
						$aux -> desabilitarStock($cod_stock);
						$cant_total = $cant_total - $cant_stock;
					}
					
					
				}
			}
		

		}

		
		
		//Materia prima a uso y entrega solo a comensal  
		if ($tipo_mov == 9||$tipo_mov == 13) {
			
			/*
			REGISTRAR MOVIMIENTO 
			*/					
			$this -> db ->query ("INSERT INTO `registro_de_movimientos`
									(fecha ,tipo_mov, anotacion)
								VALUES
									(NOW(), $tipo_mov, '$anotacion')");	
			$cod_mov =  mysqli_insert_id($this-> db->getcn());
			
			$aux = new Stock;
			
			/*
			VERIFICAR EXISTENCIA DE INGREDIENTE EN STOCK
			*/				
			$cod_stock = $aux->stockAntiguo($cod_receta);
			if($cod_stock==null){
				Movimientos::baja($cod_mov);
				return false;
			}
			$cant_stock = $cod_stock['cantidad'];
			$cod_stock = $cod_stock['cod_stock'];
			
			
			
			while ( $cantidad > 0) {
				
				/*
				VERIFICAR EXISTENCIA DE INGREDIENTE EN STOCK
				*/			
				$cod_stock = $aux->stockAntiguo($cod_receta);
				if($cod_stock==null){
					Movimientos::baja($cod_mov);
					return false;
				}
				$cant_stock = $cod_stock['cantidad'];	
				$cod_stock = $cod_stock['cod_stock'];
				
				if($cant_stock - $cantidad > 0){
				
					/*
					DISMINUIR STOCK
					*/						
					$aux -> aumentarStock($cod_stock,-$cantidad);
					
					/*
					CAMBIAR DISPONIBILIDAD SI ES QUE HAY O NO STOCK
					*/							
					$secant = Stock::get_porstock($cod_stock)['cod_seccion'];	
					if(Stock::haystock($secant)==true)
						Secciones::cambiarDisp($secant,"Con espacio");
					else
						Secciones::cambiarDisp($secant,"Vacio");
					
					/*
					REGISTRAR LINEA MOVIMIENTO 
					*/					
					Movimientos::altaLinea($cod_stock,$cant_stock, $cant_stock - $cantidad, $cod_mov);
					
					$cantidad = 0;
				}
				if ($cant_stock - $cantidad <=0) {
					/*
					DISMINUIR STOCK
					*/		
					$aux -> aumentarStock($cod_stock,-$cant_stock);
					
					/*
					CAMBIAR DISPONIBILIDAD SI ES QUE HAY O NO STOCK
					*/						
					$secant = Stock::get_porstock($cod_stock)['cod_seccion'];	
					if(Stock::haystock($secant)==true)
						Secciones::cambiarDisp($secant,"Con espacio");
					else
						Secciones::cambiarDisp($secant,"Vacio");
					
					/*
					REGISTRAR LINEA MOVIMIENTO 
					*/					
					Movimientos::altaLinea($cod_stock, $cant_stock, $cant_stock - $cant_stock, $cod_mov);
					
					/*
					DESABILITAR STOCK
					*/	
					$aux -> desabilitarStock($cod_stock);
					
					$cantidad = $cantidad - $cant_stock;
				}
				
				
				
			}
		}

		return true;
	}
	
	private function altaLinea ($cod_stock, $cant_ant, $cant_act, $cod_mov){
		$this ->db -> query ("INSERT INTO linea_registro
								(cod_stock, cantidad_anterior, cantidad_actual, cod_mov)
							VALUES
								($cod_stock, $cant_ant,$cant_act,$cod_mov)");
	}
	
	private function baja($id){
		$this->db->query("delete from registro_de_movimientos 
										where cod_mov = $id");
		$this->db->query("delete from linea_registro
										where cod_mov = $id");
	}
}