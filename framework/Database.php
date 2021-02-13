<?php


class Database {

	private static $instance;

	private $cn;
	private $res;

	private function __construct() {}

	public static function getInstance() {
		if(!self::$instance) self::$instance=new Database;
		return self::$instance;
	}

	private function connect() {
		$this->cn = mysqli_connect("localhost","root","","bd_sgsr");
	}

	public function query($q) {
		if(!$this->cn) $this->connect();
		$this->res = mysqli_query($this->cn, $q);
		if(mysqli_error($this->cn)) 
			die("ERROR SQL: " . mysqli_error($this->cn) . " - consulta: $q");
	}

	public function fetch() {
		return mysqli_fetch_assoc($this->res);
	}

	public function fetchAll() {
		$aux = array();
		while($fila=$this->fetch()) $aux[]=$fila;
		return $aux;
	}

	public function numRows() {
		return mysqli_num_rows($this->res);
	}

	public function escape($s) {
		if(!$this->cn) $this->connect();
		return mysqli_escape_string($this->cn, $s);
	}

	public function getcn()
	{
	return $this->cn;
	}
	
}