<?php

Abstract Class ConnectionDataInterface {
	
	private $db;
	private $host;
	private $pw;
	private $user;
	private $db_type;
	
	public function __construct($param=null){
		/* void */
	}
	
	/**
	 * @Description setters methods. 
	 */
		
	public function setDB($db){
		$this->db = $db;
	}
	
	public function setHost($host){
		$this->host = $host;
	}
	
	public function setUser($user){
		$this->user = $user;
	}
	
	public function setPw($pw){
		$this->pw = $pw;
	}
	
	public function setDBType($db_type){
		$this->db_type = $db_type;
	}
	
	/**
	 * @Description getters methods. 
	 */
	
	public function getPw(){
		return $this->pw;
	}
	
	public function getUser(){
		return $this->user;
	}
	
	public function getHost(){
		return $this->host;
	}
	
	public function getDB(){
		return $this->db;
	}
	
	public function getDBType(){
		return $this->db_type;
	}
}


