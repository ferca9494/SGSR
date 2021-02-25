<?php

class Usuarios extends Model {

	public function chequearUsuario($email, $password){
		if(strlen($email)<5) return false;
		if(strlen($password)<5) return false;

		$email = $this->db->escape($email);
		$password = $this->db->escape($password);

			$this->db->query("SELECT *
								FROM usuarios
								WHERE email = '$email'");

			if($this->db->numRows()==0) return false;
			$fila = $this->db->fetch();

			if($fila['password']==sha1($password)) return $fila['usuario_id'];
			else return false;
	}

	public function getDatosByname($name){
		$this->db->query("SELECT *
						FROM usuarios
						WHERE nombre ='$name'");
		return $this->db->fetch();
	}

	
}