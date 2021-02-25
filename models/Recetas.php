<?php

class Recetas extends Model{
	
	public function Registro($detalle,$caducidad,$anotacion,$cantidad_de_uso,$uso_por_extra,$tipo,$categoria)
	{
		//validaciones
		if(strlen($detalle)<1)die("error1: detalle de receta invalido");
		if(!substr($detalle,0,60))die("error2: detalle de receta invalido");
		$this->db->escape($detalle);
		
		if(isset($anotacion)&&$anotacion!=""){
		if(strlen($anotacion)<1)die("error1: anotacion de receta invalido");
		if(!substr($anotacion,0,150))die("error2: anotacion de receta invalido");
			$this->db->escape($anotacion);
		}else
			$anotacion="";
		
		if(!ctype_digit($tipo))die("error1: tipo de receta invalido");
		$this->db->query("
		select *
		from tipo_receta
		where cod_tipo_receta = '$tipo'"
		);
		if($this->db->numRows()!=1)die("error2: tipo de receta inexistente");
		
		//--
		
		if($tipo==1||$tipo==2||$tipo==3||$tipo==6||$tipo==7)
		{
			if(!ctype_digit($categoria))die("error1: tipo de conserva de receta invalido");
			$this->db->query("
			select *
			from tipo_conserva
			where cod_tipo_conserva = '$categoria'"
			);
			if($this->db->numRows()!=1)die("error2: tipo de conserva de receta inexistente");
			if($caducidad!='s'&&$caducidad!='n')die("error2:caducidad de receta invalido");
		}
		//--
		
		if($tipo==1||$tipo==2||$tipo==3||$tipo==6)
		{
			if(!ctype_digit($cantidad_de_uso))die("error1: cantidad de uso de receta invalido");
			if($cantidad_de_uso<1)die("error2: cantidad de uso de receta invalido");
			
			if(!ctype_digit($uso_por_extra))die("error1: uso por extra de receta invalido");
			if($uso_por_extra<0)die("error2: uso por extra de receta invalido");
		}
	
		if($tipo==1||$tipo==2||$tipo==3||$tipo==6)
		{
			//registro
			$this->db->query(
			"insert into recetas
			(detalle,Es_caduco,tipo_receta,cantidad_de_uso,uso_extra,anotacion) 
			values ('$detalle','$caducidad','$tipo','$cantidad_de_uso','$uso_por_extra','$anotacion')"	
			);
			$id_receta = mysqli_insert_id($this->db->getcn());
			$this->db->query("
			insert into `tipo_conserva-receta` values ('$categoria','$id_receta')"
			);
		}
		elseif($tipo==4||$tipo==5)
		{
			//registro
			$this->db->query(
			"insert into recetas (detalle,tipo_receta,anotacion) values ('$detalle','$tipo','$anotacion')"	
			);
			$id_receta = mysqli_insert_id($this->db->getcn());
		}
		elseif($tipo==7)
		{
			//registro
			$this->db->query(
			"insert into recetas (detalle,tipo_receta,anotacion) values ('$detalle','$tipo','$anotacion')"	
			);
			$id_receta = mysqli_insert_id($this->db->getcn());
			$this->db->query("
			insert into `tipo_conserva-receta` values ('$categoria','$id_receta')"
			);
		}
		else
		{
			die("Error 3:tipo de receta invalido");
		}
		
			return $id_receta;
		
	}
	
	public function Registro_Ingrediente($rec,$ing,$cant,$upe)
	{
		//validacion
		if(!ctype_digit($rec))die("Error 1:receta invalida");
		if($rec<1)die("Error 2:receta invalida");
		if(!ctype_digit($ing))die("Error 1:ingrediente invalido");
		$this->db->query(
		"select *
		from recetas
		where cod_receta = '$ing'
		and tipo_receta >=1
		and tipo_receta <=3
		or tipo_receta = 6
		and habilitado = 's'"
		);
		if($this->db->numRows()==0)die("Error 2:ingrediente con este tipo inexistente");
		
		if(!ctype_digit($cant))die("Error 1:cantidad de ingrediente invalida");
		if(!($cant>0&&$cant<=20))die("Error 2:cantidad de ingrediente invalida");
		
		if(!ctype_digit($upe))die("Error 1:cantidad de ingrediente invalida");
		if(!($upe>=0&&$upe<=20))die("Error 2:cantidad de ingrediente invalida");
		
		//registro
		$this->db->query(
		"insert into `receta-receta` values('$ing','$rec','$cant','$upe')"
		);
	}

	
	public function get_matporreceta($id_rec)
	{
	$this->db->query(
	"select ri.detalle as det, rr.cantidad as cpu,rr.uso_por_extra as upe , ri.cod_receta as id , ri.tipo_receta as tipo
	from recetas ri ,recetas r , `receta-receta` rr
	where  r.cod_receta = '$id_rec'
	and rr.`cod_receta-ingrediente` = ri.cod_receta
	and rr.cod_receta = r.cod_receta
	and ri.habilitado = 's'
	"	
	);
	return $this->db->fetchAll();
	}
	
	public function Busca_MatPrima($detalle)
	{	
		if($detalle=="")return null;
		if(strlen($detalle)<1)die("error1: detalle de receta invalido");
		if(!substr($detalle,0,20))die("error2: detalle de receta invalido");
		$this->db->escape($detalle);
		
		$this->db->query(
		"select *,r.detalle as d,r.cod_receta as id,tr.detalle as tipo
		from recetas r, tipo_receta tr
		where r.detalle like '%$detalle%'
		and r.tipo_receta = tr.cod_tipo_receta
		and (tr.cod_tipo_receta >= 1
		and tr.cod_tipo_receta <= 3
		)
		and r.habilitado = 's'
		order by d"
		);
		if($this->db->numRows()>0)
			return $this->db->fetchAll();
		else
			return null;
	}
	public function Busca_Receta($detalle)
	{	
		if($detalle=="")return null;
		if(strlen($detalle)<1)die("error1: detalle de receta invalido");
		if(!substr($detalle,0,20))die("error2: detalle de receta invalido");
		$this->db->escape($detalle);
		
		$this->db->query(
		"select r.detalle as d,r.cod_receta as id,tr.detalle as tipo
		from recetas r, tipo_receta tr
		where r.detalle like '%$detalle%'
		and r.tipo_receta = tr.cod_tipo_receta
		and (tr.cod_tipo_receta >= 4
		and tr.cod_tipo_receta <= 7
		)
		and r.habilitado = 's'
		order by d"
		);
		if($this->db->numRows()>0)
			return $this->db->fetchAll();
		else
			return null;
	}
	public function Busca_Receta_portipo($idtipo)
	{
		if(!ctype_digit($idtipo))die("Error 1:Tipo de receta invalido");
		$this->db->query("
		select *
		from tipo_receta
		where cod_tipo_receta = $idtipo
		");
		if($this->db->numRows()==0)die("Error 2:Tipo de receta inexistente");
		
		$this->db->query(
		"select r.detalle as d,r.cod_receta as id,tr.detalle as tipo
		from recetas r, tipo_receta tr
		where r.tipo_receta = $idtipo
		and r.tipo_receta = tr.cod_tipo_receta
		and (tr.cod_tipo_receta >= 4
		and tr.cod_tipo_receta <= 7
		)
		and r.habilitado = 's'
		order by d"
		);
		if($this->db->numRows()>0)
		return $this->db->fetchAll();
		else
		return null;
	}
	public function Busca_Receta_ing($det_ing)
	{
		if(strlen($detalle)<1)die("error1: detalle de ingrediente invalido");
		if(!substr($detalle,0,20))die("error2: detalle de invalido invalido");
		$this->db->escape($detalle);
	
		$this->db->query(
		"select r.detalle as d,r.cod_receta as id,tr.detalle as tipo
		from recetas r, tipo_receta tr , `receta-receta` rr , recetas ri
		where ri.detalle like '%$det_ing%'
		and r.tipo_receta = tr.cod_tipo_receta
		and	r.cod_receta = rr.cod_receta
		and ri.cod_receta = rr.`cod_receta-ingrediente`
		and tr.cod_tipo_receta >= 4
		and tr.cod_tipo_receta <= 7
		and r.habilitado = 's'
		order by r.detalle"
		);
		return $this->db->fetchAll();
	}
	
	public function ModIngrediente($rec,$reci,$ing,$ing_cant,$ing_upe)
	{
		if(!ctype_digit($rec))die("error1: receta invalida");
		if($rec<1)die("error2: receta invalida");
		if(!ctype_digit($reci))die("error1: ingrediente1 invalido");
		if($reci<1)die("error2: ingrediente1 invalido");
		if(!ctype_digit($ing))die("error1: ingrediente2 invalido");
		if($ing<1)die("error2: ingrediente2 invalido");
		if(!ctype_digit($ing_cant))die("error1: cantidad de ingrediente invalida");
		if($ing_cant<1)die("error2: cantidad de ingrediente invalida");
		if(!ctype_digit($ing_upe))die("error1: cantidad de uso por extra de ingrediente invalida");
		if($ing_upe<0)die("error2: cantidad de uso por extra de ingrediente invalida");
		
		$this->db->query("
		update `receta-receta`
		set `cod_receta-ingrediente` = $ing , cantidad = $ing_cant , uso_por_extra = $ing_upe
		where cod_receta = $rec
		and `cod_receta-ingrediente` = $reci 
		");
	}
	
	public function getIngre($rec,$reci)
	{
		$this->db->query("
		select `cod_receta-ingrediente` as reci , cantidad , uso_por_extra
		from `receta-receta`
		where  cod_receta = $rec
		and `cod_receta-ingrediente` = $reci 
		");
		return $this->db->fetch();
	}
	
	public function Lista_Recetas_matpro()
	{
		$this->db->query(
		"select r.detalle as d,r.cod_receta as id,tr.detalle as tipo
		from recetas r	
		left join tipo_receta tr
		on r.tipo_receta = tr.cod_tipo_receta
		where tr.cod_tipo_receta = 6
		and r.habilitado = 's'
		order by d"
		);
		return $this->db->fetchAll();
	}
	public function Lista_Recetas_recdep()
	{
		$this->db->query(
		"select r.detalle as d,r.cod_receta as id,tr.detalle as tipo
		from recetas r	
		left join tipo_receta tr
		on r.tipo_receta = tr.cod_tipo_receta
		where tr.cod_tipo_receta = 7
		and r.habilitado = 's'
		order by d"
		);
		return $this->db->fetchAll();
	}
	public function Lista_Recetas_Empl()
	{
		$this->db->query(
		"select r.detalle as d,r.cod_receta as id,tr.detalle as tipo
		from recetas r	
		left join tipo_receta tr
		on r.tipo_receta = tr.cod_tipo_receta
		where tr.cod_tipo_receta = 5
		and r.habilitado = 's'
		order by d"
		);
		return $this->db->fetchAll();
	}
	public function Lista_Recetas_devolvibles()
	{
		$this->db->query(
		"select r.detalle as d,r.cod_receta as id,tr.detalle as tipo
		from recetas r
		
		left join tipo_receta tr
		on r.tipo_receta = tr.cod_tipo_receta
		
		where (tr.cod_tipo_receta = 2
	    or tr.cod_tipo_receta = 3
		or tr.cod_tipo_receta = 7)
		and r.habilitado = 's'
		
		order by d"
		);
		return $this->db->fetchAll();
	}
	public function Lista_Recetas_pedibles()
	{
		$this->db->query(
		"select r.detalle as d,r.cod_receta as id,tr.detalle as tipo
		from recetas r
		
		left join tipo_receta tr
		on r.tipo_receta = tr.cod_tipo_receta
		
		where (tr.cod_tipo_receta >= 2
		or tr.cod_tipo_receta <= 7)
		and r.habilitado = 's'
		
		order by d"
		);
		return $this->db->fetchAll();
	}
	public function Lista_Recetas()
	{
		$this->db->query(
		"select r.detalle as d,r.cod_receta as id,tr.detalle as tipo
		from recetas r
		
		left join tipo_receta tr
		on r.tipo_receta = tr.cod_tipo_receta
		
		where (tr.cod_tipo_receta = 4
	    or tr.cod_tipo_receta = 5
		or tr.cod_tipo_receta = 6
		or tr.cod_tipo_receta = 7)
		and r.habilitado = 's'
		
		order by d"
		);
		return $this->db->fetchAll();
	}
	
	public function Lista_Matprima()
	{
		$this->db->query(
		"select r.detalle as d,r.cod_receta as id,tr.detalle as tipo,Cantidad_de_uso,Uso_extra,Es_caduco,Anotacion
		from recetas r
		
		left join tipo_receta tr
		on r.tipo_receta = tr.cod_tipo_receta
		
		where r.habilitado = 's'
		and(
		tr.cod_tipo_receta = 1
		or tr.cod_tipo_receta = 2
		or tr.cod_tipo_receta = 3
		)
		order by d"
		);
		return $this->db->fetchAll();
	}
	public function Lista_Matprimaypro()
	{
		$this->db->query(
		"select r.detalle as d,r.cod_receta as id,tr.detalle as tipo,Cantidad_de_uso,Uso_extra,Es_caduco,Anotacion
		from recetas r
		
		left join tipo_receta tr
		on r.tipo_receta = tr.cod_tipo_receta
		
		where r.habilitado = 's'
		and(
		tr.cod_tipo_receta = 1
		or tr.cod_tipo_receta = 2
		or tr.cod_tipo_receta = 3
		or tr.cod_tipo_receta = 6
		)
		order by d"
		);
		return $this->db->fetchAll();
	}
	public function get_porid($id)
	{
		$this->db->query(
		"select * ,tr.detalle as tipo,r.detalle as nombre,Uso_extra
		from recetas r , `tipo_conserva-receta` crr,tipo_receta tr
		where r.cod_receta = '$id'
		and r.tipo_receta = tr.cod_tipo_receta
		and r.cod_receta = crr.cod_receta
		and r.habilitado = 's'
		"
		);
		return $this->db->fetch();
	}
	public function get_porid2($id)
	{
		$this->db->query(
		"select * ,tr.detalle as tipo,r.detalle as nombre,Uso_extra
		from recetas r,tipo_receta tr
		where r.cod_receta = '$id'
		and r.tipo_receta = tr.cod_tipo_receta
		and r.habilitado = 's'"
		);
		return $this->db->fetch();
	}
	public function Modificar($rec,$detalle,$caducidad,$anotacion,$cantidad_de_uso,$uso_por_extra,$tipo,$categoria)
	{
		//validaciones
		if(!ctype_digit($rec))die("Error 1:receta invalida");
		$this->db->query(
		"select *
		from recetas
		where cod_receta = '$rec'
		and habilitado = 's'"
		);
		if($this->db->numRows()==0)die("Error 2:receta con este tipo inexistente");
		
		if(strlen($detalle)<1)die("error1: detalle de receta invalido");
		if(!substr($detalle,0,20))die("error2: detalle de receta invalido");
		$this->db->escape($detalle);
		
		if(isset($anotacion)&&$anotacion!=""){
		if(strlen($anotacion)<1)die("error1: anotacion de receta invalido");
		if(!substr($anotacion,0,50))die("error2: anotacion de receta invalido");
		$this->db->escape($anotacion);
		}
		else
		$anotacion="";
		
		if(!ctype_digit($tipo))die("error1: tipo de receta invalido");
		$this->db->query("
		select *
		from tipo_receta
		where cod_tipo_receta = '$tipo'"
		);
		if($this->db->numRows()!=1)die("error2: tipo de receta inexistente");
		
		//--
		
		if($tipo==1||$tipo==2||$tipo==3||$tipo==6||$tipo==7)
		{

			if(!ctype_digit($categoria))die("error1: tipo de conserva de receta invalido");
			$this->db->query("
			select *
			from tipo_conserva
			where cod_tipo_conserva = '$categoria'"
			);
			if($this->db->numRows()!=1)die("error2: tipo de conserva de receta inexistente");
		}
		//--
		
		if($tipo==1||$tipo==2||$tipo==3||$tipo==6)
		{
			if(!ctype_digit($cantidad_de_uso))die("error1: cantidad de uso de receta invalido");
			if($cantidad_de_uso<1)die("error2: cantidad de uso de receta invalido");
			
			if(!ctype_digit($uso_por_extra))die("error1: uso por extra de receta invalido");
			if($uso_por_extra<0)die("error2: uso por extra de receta invalido");
		
			if($caducidad!='s'&&$caducidad!='n')die("error2:caducidad de receta invalido");
		}
	
		if($tipo==1||$tipo==2||$tipo==3||$tipo==6)
		{
			//registro
			$this->db->query(
			"update recetas
			set detalle = '$detalle', 
			Es_caduco = '$caducidad', 
			Anotacion = '$anotacion' ,
			Cantidad_de_uso = '$cantidad_de_uso', 
			Uso_extra = '$uso_por_extra',
			tipo_receta = '$tipo'
			where cod_receta = '$rec' "
			);
		}
		elseif($tipo==4||$tipo==5)
		{
			//registro
			$this->db->query(
			"update recetas
			set detalle = '$detalle', 
			Anotacion = '$anotacion' ,
			tipo_receta = '$tipo'
			where cod_receta = '$rec'"
			);
		
		}
		elseif($tipo==7)
		{
			
			$this->db->query(
			"update recetas
			set detalle = '$detalle', 
			Es_caduco = '$caducidad', 
			Anotacion = '$anotacion' ,
			Cantidad_de_uso = '$cantidad_de_uso', 
			Uso_extra = '$uso_por_extra',
			tipo_receta = '$tipo'
			where cod_receta = '$rec' "
			);
			
			$this->db->query(
			"update `tipo_conserva-receta`
			set cod_tipo_conserva = '$categoria'
			where cod_receta = '$rec'"
			);
		}
		else
		{
			die("Error 3:tipo de receta invalido");
		}
		
	}
	
	public function Baja($id_r)
	{
		if(!ctype_digit($id_r))die("error1: receta invalida");
		$this->db->query("
		select *
		from recetas
		where cod_receta = '$id_r'"
		);
		if($this->db->numRows()!=1)die("error2: receta inexistente");	
	
		$this->db->query(
		"update recetas
		set habilitado = 'n'
		where cod_receta = '$id_r'
		and habilitado = 's'
		"
		);

		
	}

	//desde aca esta hecho por gari
	
		public function getTodos(){
			$this->db->query("SELECT * 
								FROM recetas
								where habilitado = 's"); 
			return $this->db->fetchAll();
		}

		public function getTodosPedCom(){
			$this->db->query("SELECT * 
								FROM recetas
								WHERE tipo_receta = 4
								and habilitado = 's'"); 
			return $this->db->fetchAll();
		}

		public function getTodosPedEmpl(){
			$this->db->query("SELECT * 
								FROM recetas
								WHERE tipo_receta = 5
								and habilitado = 's'"); 
			return $this->db->fetchAll();
		}

		public function getTodosMP(){
			$this->db->query("SELECT * 
								FROM recetas
								WHERE tipo_receta = 3
								or tipo_receta = 2
								and habilitado = 's' "); 
			return $this->db->fetchAll();
		}
		public function getTodosPD(){
			$this->db->query("SELECT * 
								FROM recetas
								WHERE tipo_receta = 7
								and habilitado = 's' "); 
			return $this->db->fetchAll();
		}
		public function existeRecMP ($id){
			
			if(!ctype_digit($id)) die('error 1');
			
			$this -> db ->query ("SELECT *
									FROM recetas
									WHERE tipo_receta <= 3
									and	tipo_receta >=1								
									and cod_receta = $id
									and habilitado = 's'");
			if($this-> db -> numRows()==0) return false;
			else return true;
		}
		public function existeRecPD ($id){
			
			if(!ctype_digit($id)) die('error 1');
			
			$this -> db ->query ("SELECT *
									FROM recetas
									WHERE tipo_receta = 7							
									and cod_receta = $id
									and habilitado = 's'");
			if($this-> db -> numRows()==0) return false;
			else return true;
		}
		public function existeRecEmpl ($id){
			
			if(!ctype_digit($id)) die('error 1');
			
			$this -> db ->query ("SELECT *
									FROM recetas
									WHERE tipo_receta = 5 
									and cod_receta = $id
									and habilitado = 's'
									");
			if($this-> db -> numRows()==0) return false;
			else return true;
		}
		
		public function existeRecPedCom ($id){
			
			if(!ctype_digit($id)) die('error 1');
			
			$this -> db ->query ("SELECT *
									FROM recetas
									WHERE tipo_receta = 4 and cod_receta = $id
									and habilitado = 's'
									");
			if($this-> db -> numRows()==0) return false;
			else return true;
		}

		public function ingredientes($cod_receta){

			$this ->db -> query ("SELECT rr.`cod_receta-ingrediente` as 'ingrediente', ri.detalle ,rr.cantidad,rr.uso_por_extra
									FROM `receta-receta` rr , recetas r, recetas ri
									WHERE r.cod_receta = '$cod_receta'
									and rr.cod_receta = r.cod_receta
									and ri.cod_receta = rr.`cod_receta-ingrediente`
									and r.habilitado = 's'
									and ri.habilitado = 's'
									");

			return $this->db->fetchAll();


		}
		public function es_caduco ($id){
			
			if(!ctype_digit($id)) die('error 1');
			
			$this -> db ->query ("SELECT Es_caduco
									FROM recetas
									WHERE cod_receta = '$id'
									and habilitado = 's'
									");
			if($this-> db ->fetch()['Es_caduco']=="s")
				return true;
			else
				return false;
		}
	
}