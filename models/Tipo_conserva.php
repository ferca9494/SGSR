<?php

class Tipo_conserva extends Model
{

	public function getTodo()
	{
		$this->db->query(
		"select *
		from tipo_conserva"
		);
		return $this->db->fetchAll();
	}
	public function getTCreceta($id)
	{
		$this->db->query(
		"select *
		from tipo_conserva tc,`tipo_conserva-receta` tcr
		where tcr.cod_receta = $id
		and tc.cod_tipo_conserva = tcr.cod_tipo_conserva
		"
		);
		if($this->db->numRows()>0)
		return $this->db->fetch();
		else
		return null;
	}
	/*
	public function getTCseccion($id)
	{
		$this->db->query(
		"select *
		from tipo_conserva,`tipo_conserva-seccion` tcr
		where tcr.cod_seccion = $id
		"
		);
		if($this->db->numRows()>0)
		return $this->db->fetch();
		else
		return null;
	}
	*/
}

?>