<?php

class Contenedores extends Model
{

	public function Registro($detalle,$dep)
	{
		//validaciones
		if(strlen($detalle)<1)die("error1: detalle de contenedor invalido");
		if(!substr($detalle,0,20))die("error2: detalle de contenedor invalido");
		$this->db->escape($detalle);
		
		if(!ctype_digit($dep))die("Error 1:deposito de contenedor invalido");
		$this->db->query("
		select *
		from depositos
		where cod_deposito = '$dep'
		");
		if($this->db->numRows()!=1)die("Error 2:deposito de contenedor inexistente");
		
		//registros
		$this->db->query(
		"insert into Contenedores (cod_deposito,detalle) value('$dep','$detalle')"
		);
		return mysqli_insert_id($this->db->getcn());
	}
	
	public function Modificar($id,$detalle,$dep)
	{
	
		//validaciones
		if(!ctype_digit($id))die("Error 1:contenedor invalido");
		$this->db->query("
		select *
		from contenedores
		where cod_contenedor = '$id'
		");
		if($this->db->numRows()==0)die("Error 2:contenedor inexistente");
		
		if(strlen($detalle)<1)die("error1: detalle de contenedor invalido");
		if(!substr($detalle,0,20))die("error2: detalle de contenedor invalido");
		$this->db->escape($detalle);
		
		if(!ctype_digit($dep))die("Error 1:deposito de contenedor invalido");
		$this->db->query("
		select *
		from depositos
		where cod_deposito = '$dep'
		");
		if($this->db->numRows()==0)die("Error 2:deposito de contenedor inexistente");
		
		//registros
		$this->db->query(
		"
		update contenedores
		set detalle = '$detalle' , cod_deposito = $dep
		where cod_contenedor = $id
		"
		);
	}
	
	public function get_porid($id)
	{
		$this->db->query(
		"select *
		from contenedores
		where habilitado = 's'
		and cod_contenedor = $id"
		);	
		return $this->db->fetch();
	}
	
	public function getdedeposito($dep)
	{
		$this->db->query(
		"select *
		from Contenedores
		where cod_deposito = '$dep'
		and habilitado = 's'"
		);
		
		if($this->db->numRows()>0)
		return $this->db->fetchAll();
		else
		return null;	
	}
	
	public function getTodos()
	{
		$this->db->query(
		"select c.cod_contenedor as id , c.detalle as con , d.detalle as dep
		from Contenedores c , depositos d
		where c.habilitado = 's'
		and d.habilitado = 's'
		and c.cod_deposito = d.cod_deposito
		"
		);
		return $this->db->fetchAll();
	
	}
	
	public function Baja($con)
	{
		if(!ctype_digit($dep))die("Error 1:contenedor invalido");
		$this->db->query("
		select *
		from contenedores
		where cod_contenedor = '$con'
		");
		if($this->db->numRows()==0)die("Error 2:contenedor inexistente");
	
		$this->db->query(
		"update contenedores 
		set habilitado = 'n'
		where cod_contenedor = '$con'
		"
		);
		$this->db->query(
		"update secciones
		set habilitado = 'n'
		where cod_contenedor = '$con'
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