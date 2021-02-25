<?php

class Secciones extends Model
{

	public function Registro($detalle,$con,$tipo)
	{
		//validaciones
		if(strlen($detalle)<1)die("error1: detalle de seccion invalido");
		if(!substr($detalle,0,20))die("error2: detalle de seccion invalido");
		$this->db->escape($detalle);
		
		if(!ctype_digit($con))die("Error 1:contenedor de seccion invalido");
		$this->db->query("
		select *
		from contenedores
		where cod_contenedor = '$con'
		");
		if($this->db->numRows()!=1)die("Error 2:contenedor de seccion inexistente");
		
		if(!ctype_digit($tipo))die("Error 1:tipo de conserva de seccion invalido");
		$this->db->query("
		select *
		from tipo_conserva
		where cod_tipo_conserva = '$tipo'
		");
		if($this->db->numRows()!=1)die("Error 2:tipo de conserva de seccion inexistente");
		
		//registros
		$this->db->query(
		"insert into Secciones (detalle,cod_contenedor) value('$detalle','$con')"
		);
		
		$id_sec = mysqli_insert_id($this->db->getcn());
		
		$this->db->query(
		"insert into `tipo_conserva-seccion` value('$tipo','$id_sec')"
		);
	}
	
	public function Modificar($id,$detalle,$tipo)
	{
		//validaciones
		if(!ctype_digit($id))die("Error 1:seccion invalida");
		$this->db->query("
		select *
		from secciones
		where cod_seccion = '$id'
		");
		if($this->db->numRows()==0)die("Error 2:seccion inexistente");
		
		if(strlen($detalle)<1)die("error1: detalle de seccion invalido");
		if(!substr($detalle,0,20))die("error2: detalle de seccion invalido");
		$this->db->escape($detalle);
		
		if(!ctype_digit($tipo))die("Error 1:tipo de conserva de seccion invalido");
		$this->db->query("
		select *
		from tipo_conserva
		where cod_tipo_conserva = '$tipo'
		");
		if($this->db->numRows()!=1)die("Error 2:tipo de conserva de seccion inexistente");
		
		//registros
		$this->db->query(
		"
		update secciones
		set detalle = '$detalle' 
		where cod_seccion = $id
		"
		);
		$this->db->query(
		"
		update `tipo_conserva-seccion`
		set cod_tipo_conserva = $tipo
		where cod_seccion = $id
		"
		);
		
	}
	
	public function get_porid($id)
	{
		$this->db->query(
		"select * , ts.cod_tipo_conserva as tipo
		from secciones s , `tipo_conserva-seccion` ts
		where s.habilitado = 's'
		and s.cod_seccion = ts.cod_seccion
		and s.cod_seccion = $id"
		);	
		return $this->db->fetch();
	}
	
	public function getdecont($con)
	{
		$this->db->query(
		"select *, t.detalle as tipo,s.detalle as det
		from secciones s , `tipo_conserva-seccion` ts,tipo_conserva t
		where s.cod_contenedor = '$con'
		and s.habilitado = 's'
		and s.cod_seccion = ts.cod_seccion
		and t.cod_tipo_conserva = ts.cod_tipo_conserva
		"
		);
		if($this->db->numRows()>0)
		return $this->db->fetchAll();
		else
		return null;
	}
	
	public function get_por_tipo_conservacion($id,$aldepespecial)
	{
		if(!$aldepespecial)
		$this->db->query(
		"select s.cod_seccion as id , s.detalle as s, c.detalle as c, d.detalle as d
		from Secciones s , `tipo_conserva-seccion` crs ,`tipo_conserva-receta` crr,contenedores c,depositos d
		where crr.cod_receta = '$id'
		and crs.cod_seccion = s.cod_seccion
		and crs.cod_tipo_conserva = crr.cod_tipo_conserva 
		and s.disponibilidad <> 'Lleno'
		and s.cod_contenedor = c.cod_contenedor
		and c.cod_deposito = d.cod_deposito
		and d.habilitado = 's'
		and c.habilitado = 's'
		and s.habilitado = 's'
        "
		);	
		else
		$this->db->query(
		"select s.cod_seccion as id , s.detalle as s, c.detalle as c
		from Secciones s , `tipo_conserva-seccion` crs ,`tipo_conserva-receta` crr,contenedores c
		where crr.cod_receta = '$id'
		and crs.cod_seccion = s.cod_seccion
		and crs.cod_tipo_conserva = crr.cod_tipo_conserva 
		and s.disponibilidad <> 'Lleno'
		and s.cod_contenedor = c.cod_contenedor
		and c.cod_deposito = 50
		and d.habilitado = 's'
		and c.habilitado = 's'
		and s.habilitado = 's'"
		);	
		
		if($this->db->numRows()>0)
			return $this->db->fetchAll();
		else
			return null;
	}

	public function cambiarDisp($sec,$disp)
	{
		if(!ctype_digit($sec))die("Error 1:seccion invalida");
		$this->db->query("
		select *
		from secciones
		where cod_seccion = '$sec'
		");
		if($this->db->numRows()==0)die("Error 2:seccion inexistente");
		if($disp!="Lleno"&&$disp!="Con espacio"&&$disp!="Vacio")die("error: disponibilidad invalida");
		
		$this->db->query(
		"update secciones 
		set disponibilidad = '$disp'
		where cod_seccion = '$sec'
		"
		);
	}
	
	public function Baja($sec)
	{
		if(!ctype_digit($sec))die("Error 1:seccion invalida");
		$this->db->query("
		select *
		from secciones
		where cod_seccion = '$sec'
		");
		if($this->db->numRows()==0)die("Error 2:seccion inexistente");
	
		$this->db->query(
		"update secciones 
		set habilitado = 'n'
		where cod_seccion = '$sec'
		"
		);
		$sec = mysqli_insert_id($this->db->getcn());
		$this->db->query(
		"update stock
		set habilitado = 'n'
		where cod_seccion = '$sec'
		"
		);
	}
}

?>
