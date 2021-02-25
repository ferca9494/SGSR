<?php

class Tipo_receta extends Model
{
	
	public function getTodo()
	{
		$this->db->query(
		"select *
		from Tipo_receta"
		);
		return $this->db->fetchAll();
	}
	public function getTMatPrima()
	{
		$this->db->query(
		"select *
		from Tipo_receta
		where cod_tipo_receta >= 1
		and cod_tipo_receta <= 3
		"
		);
		return $this->db->fetchAll();
	}
	public function getTReceta()
	{
		$this->db->query(
		"select *
		from Tipo_receta
		where cod_tipo_receta >= 4
		and cod_tipo_receta <= 7
		"
		);
		return $this->db->fetchAll();
	}
	
	public function getTRecetaCyE()
	{
		$this->db->query(
		"select *
		from Tipo_receta
		where cod_tipo_receta = 4
		or cod_tipo_receta = 5
		"
		);
		return $this->db->fetchAll();
	}
	public function get_porid($id)
	{
		$this->db->query(
		"select *
		from Tipo_receta t , recetas r
		where t.cod_tipo_receta = r.tipo_receta
		and r.cod_receta = $id
		"
		);
		return $this->db->fetch();
	}

	
}

?>