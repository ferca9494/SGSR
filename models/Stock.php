<?php

class Stock extends Model{
	
public function Registro_fantasma($cantidad,$fecha_venc,$rec,$disp)
	{
		//validaciones
		if(!ctype_digit($cantidad))die("Error 1:cantidad de stock invalida");
		if($cantidad<0)die("Error 2:cantidad de stock invalida");
		if($cantidad>10000)die("Error 3:cantidad de stock invalida");	
				
		if(!ctype_digit($rec))die("error1: materia a guardar de stock invalida");
		$this->db->query("
		select *
		from recetas
		where cod_receta = '$rec'"
		);
		if($this->db->numRows()!=1)die("error2: materia a guardar de stock inexistente");	
		
		if($disp!="Lleno"&&$disp!="Con espacio"&&$disp!="Vacio")die("error: disponibilidad invalida");
		
		if(!Recetas::es_caduco($rec))
			$fecha_venc = null;
		
		$this->db->query("select s.cod_seccion as cod
		from secciones s 
		where s.cod_seccion = 11
		and s.disponibilidad = 'Vacio'
		limit 1");
		$sec = $this->db->fetch()['cod'];
		
		//registro
		$this->db->query(
		"update secciones 
		set disponibilidad = '$disp'
		where cod_seccion = '$sec'
		"
		);
		
		$this->db->query(
		"insert into stock (cantidad,fecha_vencimiento,cod_receta,cod_seccion,fecha_ingreso) 
		values ('$cantidad','$fecha_venc','$rec','$sec',NOW())"	
		);
	}
	public function Registro($cantidad,$fecha_venc,$rec,$sec,$disp)
	{
		//validaciones
		if(!ctype_digit($cantidad))die("Error 1:cantidad de stock invalida");
		if($cantidad<0)die("Error 2:cantidad de stock invalida");
		if($cantidad>10000)die("Error 3:cantidad de stock invalida");	
		
		if($disp!="Lleno"&&$disp!="Con espacio")die("error: disponibilidad invalida");		
		//$partes = explode("/", $fecha_venc);
		
		//If (!checkdate ($partes[1],$partes[0],$partes[2]))die("Error1:fecha de vencimiento de stock invalida");
		
		if(!ctype_digit($rec))die("error1: materia a guardar de stock invalida");
		$this->db->query("
		select *
		from recetas
		where cod_receta = '$rec'"
		);
		if($this->db->numRows()!=1)die("error2: materia a guardar de stock inexistente");	
		
		if(!ctype_digit($sec))die("error1: seccion invalida");
		$this->db->query("
		select *
		from secciones
		where cod_seccion = '$sec'
		and disponibilidad <> 'Lleno'"
		);
		if($this->db->numRows()!=1)die("error2: seccion inexistente");	
			
		if(!Recetas::es_caduco($rec))
				$fecha_venc = null;
		
		$this->db->query(
		"update secciones 
		set disponibilidad = '$disp'
		where cod_seccion = '$sec'
		"
		);
		
		$this->db->query(
			"insert into stock (cantidad,fecha_vencimiento,cod_receta,cod_seccion,fecha_ingreso) 
			values ('$cantidad','$fecha_venc','$rec','$sec',NOW())"	
		);
	}
	
	public function Buscar_poring($ing)
	{
		if(strlen($ing)<1)die("error1: detalle de ingrediente invalido");
		if(!substr($ing,0,20))die("error2: detalle de ingrediente invalido");
		$this->db->escape($ing);
		
		//wip
		$this->db->query(
		"select *,r.detalle as r ,se.detalle as s ,c.detalle as c,d.detalle as d
		from stock s , recetas r, secciones se, contenedores c, depositos d
		where r.detalle like '%$ing%'
		and s.cod_seccion = se.cod_seccion
		and se.cod_contenedor = c.cod_contenedor
		and c.cod_deposito = d.cod_deposito
		and s.cod_receta = r.cod_receta
		and s.cantidad>0
		and s.habilitado = 's'
		and se.habilitado = 's'
		and c.habilitado = 's'
		and d.habilitado = 's'
		and r.habilitado = 's'
		order by r.detalle"
		);
		if($this->db->numRows()>0)
			return $this->db->fetchAll();
		else
			return null;
	}
	public function Buscar_caducado_poring()
	{
		//wip
		$this->db->query(
		"select *,r.detalle as r ,se.detalle as s ,c.detalle as c,d.detalle as d
		from stock s , recetas r, secciones se, contenedores c, depositos d
		where s.fecha_vencimiento < curdate()
		and s.cod_seccion = se.cod_seccion
		and se.cod_contenedor = c.cod_contenedor
		and c.cod_deposito = d.cod_deposito
		and s.cod_receta = r.cod_receta
		and s.cantidad>0
		and s.habilitado = 's'
		and se.habilitado = 's'
		and c.habilitado = 's'
		and d.habilitado = 's'
		and r.habilitado = 's'
		order by s.fecha_vencimiento"
		);
		if($this->db->numRows()>0)
			return $this->db->fetchAll();
		else
			return null;
	}
	public function Buscar_casicaducado_poring()
	{

		$this->db->query(
		"select *,r.detalle as r ,se.detalle as s ,c.detalle as c,d.detalle as d
		from stock s , recetas r, secciones se, contenedores c, depositos d
		where s.fecha_vencimiento > curdate()
		and s.cod_seccion = se.cod_seccion
		and se.cod_contenedor = c.cod_contenedor
		and c.cod_deposito = d.cod_deposito
		and s.cod_receta = r.cod_receta
		and s.cantidad>0
		and s.habilitado = 's'
		and se.habilitado = 's'
		and c.habilitado = 's'
		and d.habilitado = 's'
		and r.habilitado = 's'
		order by s.fecha_vencimiento"
		);
		if($this->db->numRows()>0)
			return $this->db->fetchAll();
		else
			return null;
	}	
	
	public function get_porseccion($sec)
	{
		$this->db->query(
		"select s.cod_stock id ,r.detalle as detalle_rec ,r.cod_receta as id_rec, s.cantidad as stock, s.fecha_ingreso as fecha,s.fecha_vencimiento as fecha_ven,r.Es_caduco as caduco
		from stock s , recetas r,secciones se
		where s.cod_seccion = '$sec'
		and s.cod_receta = r.cod_receta
		and s.cantidad > 0
		and s.habilitado = 's'
		and se.cod_seccion = s.cod_seccion
		order by s.fecha_ingreso"
		);
		if($this->db->numRows()>0)
		return $this->db->fetchAll();
		else
		return null;
	}
	public function get_porid($id)
	{
		$this->db->query(
		"select *
		from stock st,secciones se
		where cantidad > 0
		and st.cod_stock = $id
		and st.cod_seccion = se.cod_seccion
		"
		);
		return $this->db->fetch();
	}

	//desde aca esta hecho por gari
	
	public function getTodos(){
			$this->db->query("SELECT * 
								FROM stock
								WHERE Habilitado = 's' "); 
			return $this->db->fetchAll();
		}
		
		public function existeStock ($id){
			
			if(!ctype_digit($id)) die('error 1');
			
			$this -> db ->query ("SELECT *
									FROM stock
									WHERE Habilitado = 's' and cod_stock = '$id'");
			if($this-> db -> numRows()!=1) return false;
			else return true;
		}

		public function stockAntiguo($cod_receta)
		{
			$this-> db -> query(" 
			SELECT s.cod_stock, s.cantidad
			 FROM stock s, secciones se,contenedores c,recetas r,depositos d
			WHERE s.Habilitado = 's' 
			and c.cod_deposito = d.cod_deposito
			and d.detalle like 'Especial'
			and se.cod_contenedor = c.cod_contenedor
			and se.cod_seccion = s.cod_seccion
			and r.cod_receta = s.cod_receta
			and s.cantidad>0
			and s.habilitado='s'
			and (s.fecha_vencimiento > curdate()
            or r.Es_caduco = 'n'  )
			and s.cod_receta = $cod_receta
			ORDER BY s.fecha_ingreso asc
			limit 1"
			);	
			if($this -> db->numRows()>0)
				return $this -> db -> fetch();
			else
				return null;
		}
		
		public function stockdereceta($cod_receta)
		{
		
			$this-> db -> query(" 
            SELECT sum(s.cantidad) as cantidad
			FROM stock s, secciones se,contenedores c,depositos d
			WHERE s.Habilitado = 's' 
			and c.cod_deposito = d.cod_deposito
			and d.detalle like 'Especial'
		
			and se.cod_contenedor = c.cod_contenedor
			and se.cod_seccion = s.cod_seccion
			and s.cod_receta = $cod_receta' 
           GROUP by s.cod_receta

			"
			);
		
		return $this -> db -> fetch();
		}
		
		public function aumentarStock($cod_stock, $cantidad){
			$this ->db -> query ("UPDATE stock
								SET cantidad = cantidad + '$cantidad' 
								WHERE cod_stock = '$cod_stock' 
								LIMIT 1");
		}
		public function cambiarCantidadStock($cod_stock, $cantidad){

			$this ->db -> query ("UPDATE stock
								SET cantidad = '$cantidad' 
								WHERE cod_stock ='$cod_stock' 
								and habilitado = 's'
								LIMIT 1");
		}

		public function desabilitarStock($cod_stock){

			$this-> db  -> query("UPDATE stock
								SET Habilitado = 'n'
								WHERE cod_stock ='$cod_stock' LIMIT 1");
		}

		public function cambiarStock($cod_stock,$cantidad ,$fecha_vencimiento){

			$this-> db -> query("UPDATE Stock
									SET cantidad = '$cantidad', fecha_vencimiento = '$fecha_vencimiento'
									WHERE cod_stock ='$cod_stock'
									LIMIT 1");

		}

		public function cantAntStock($cod_stock){
			$this-> db -> query("SELECT cantidad
									FROM stock
									WHERE cod_stock ='$cod_stock'
									LIMIT 1");

		return $this -> db -> fetch();

		}

		public function cambiarSeccion($cod_stock, $cod_seccion){
			$this-> db -> query ("UPDATE stock
									SET cod_seccion = '$cod_seccion'
									WHERE cod_stock ='$cod_stock' LIMIT 1");

		}
		
		//para solo ingredientes
		public function stockSuficienteMP($cod_receta, $cantidad){

            $this-> db -> query ("
									   SELECT sum(s.cantidad) as cantidad
										FROM stock s, secciones se,contenedores c,depositos d
										WHERE s.Habilitado = 's' 
										and c.cod_deposito = d.cod_deposito
										and d.detalle like 'Especial'
									
										and se.cod_contenedor = c.cod_contenedor
										and se.cod_seccion = s.cod_seccion
										and s.cod_receta = $cod_receta
									   GROUP by s.cod_receta
                                    ");

            if($this-> db -> numRows()==0) return false;
           
            $cantTotal = new stock;
            $cantTotal = $this->db->fetch();
            $cantTotal = $cantTotal['cantidad'];
            if ($cantTotal < $cantidad) return false;
            else return true;

        }
		//usar al entrar al movimiento asi no realiza movimientos
		//para movimientos de realizacion de recetas
        public function stockSuficienteRec($cod_receta, $cantidad){

            $rec = new Recetas;

            $rec = $rec -> ingredientes($cod_receta);

            foreach ($rec as $key => $value) {

                $cant_uso = $value['cantidad'];

                $cod_ingrediente = $value['ingrediente'];

                $flag = new Stock;

                $flag = $flag -> stockSuficienteMP($cod_ingrediente, $cantidad*$cant_uso);

                if ($flag == false) return false;
            }
            return true;
        }
		public function haystock($sec)
		{
			$this->db->query(
			"
			select *
			from stock
			where cod_seccion = $sec
			and habilitado = 's'
			and cantidad > 0
			"
			);
			if($this->db->numRows()>1)
			return true;
			else
			return false;
		
		}
		public function get_porstock($stock)//*
		{
			$this->db->query(
			"select *
			from stock
			where cod_stock = $stock "
			);	
			if($this->db->numRows()==0)die("no existe el stock");
			$this->db->query(
			"select cod_seccion
			from stock
			where cod_stock = $stock
			limit 1
			"
			);	
			return $this->db->fetch();
		}
	
}