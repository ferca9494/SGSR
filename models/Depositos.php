<?php

class Depositos extends Model
{

	public function Registro($detalle)
	{
		//validaciones
		if(strlen($detalle)<1)die("error1: detalle de deposito invalido");
		if(!substr($detalle,0,20))die("error2: detalle de deposito invalido");
		$this->db->escape($detalle);
		
		//registros
		$this->db->query(
		"insert into depositos (detalle) value ('$detalle')"
		);
		return mysqli_insert_id($this->db->getcn());
	}
	
	public function Modificar($id,$detalle)
	{
		//validaciones
		if(!ctype_digit($id))die("Error 1:deposito invalido");
		$this->db->query("
		select *
		from depositos
		where cod_deposito = '$id'
		");
		if($this->db->numRows()==0)die("Error 2:deposito inexistente");
		
		if(strlen($detalle)<1)die("error1: detalle de deposito invalido");
		if(!substr($detalle,0,20))die("error2: detalle de deposito invalido");
		$this->db->escape($detalle);
		
		
		//registros
		$this->db->query(
		"
		update depositos
		set detalle = '$detalle'
		where cod_deposito = $id
		"
		);
	}
	
	public function get_porid($id)
	{
		$this->db->query(
		"select *
		from depositos
		where habilitado = 's'
		and cod_deposito = $id"
		);	
		return $this->db->fetch();
	}
	public function getTodos()
	{
		$this->db->query(
		"select *
		from depositos
		where habilitado = 's'"
		);
		if($this->db->numRows()>0)
		return $this->db->fetchAll();
		else
		return null;
	}
	public function getEspecial()
	{
		$this->db->query(
		"select *
		from depositos
		where detalle like 'Especial'
		and  habilitado = 's'"
		);		
		
		return $this->db->fetch();
		
		
	}
	public function getnoEspecial()
	{
		$this->db->query(
		"select *
		from depositos
		where detalle not like 'Especial'
		and  habilitado = 's'"
		);		
		if($this->db->numRows()>0)
		return $this->db->fetchAll();
		else
		return null;
	}
	public function getporIngrediente($ing)
	{
		$this->db->query(
		"select d.detalle, d.cod_deposito
		from depositos d,contenedores c,secciones se,stock s,recetas r
		where r.detalle like '%$ing%'
		and r.cod_receta = s.cod_receta
		and s.cod_seccion = se.cod_seccion
		and 	se.cod_contenedor = c.cod_contenedor
		and 	c.cod_deposito = d.cod_deposito 
		and d.habilitado = 's'
		and c.habilitado = 's'
		and se.habilitado = 's'
		and s.habilitado = 's'
		and s.cantidad>0
		and r.habilitado = 's'
		group by d.detalle
		"
		);		
		if($this->db->numRows()>0)
		return $this->db->fetchAll();
		else
		return null;;	
	}
	
	public function Baja($dep)
	{
		if(!ctype_digit($dep))die("Error 1:deposito invalido");
		$this->db->query("
		select *
		from depositos
		where cod_deposito = '$dep'
		");
		if($this->db->numRows()==0)die("Error 2:deposito inexistente");
		
		$this->db->query(
		"update depositos
		set habilitado = 'n'
		where cod_deposito = '$dep'
		"
		);
	}
}

?>