<?php


Class Authenticator {
	
	private $login;
	private $pw;
	
	public function __construct($login=null, $pw=null){
		$this->setLogin($login);
		$this->setPw($pw);
	}
	
	
	public function auth(ConnectionDataInterface $cdi){
		
		$db = new DBServices($cdi);
		$conn = $db->connect();
		
		$email = $this->getLogin();
		$password = $this->getPw();
		 
		$sql = "SELECT count(*) total FROM users WHERE email='{$email}' AND password=MD5('{$password}')";
		$query = $conn->query($sql);
		
		if (!$query){
			error_log("Error with SQL: '{$sql}'");
			return FALSE;
		}
		$result = $query->fetch_array(MYSQLI_ASSOC);
		$conn->close();
		
		if($result['total'] != 1){
			return FALSE;
		}
		return TRUE;
	}
	
	public function setLogin($login){
		$this->login = $login;
	}
	
	public function getLogin(){
		return $this->login;
	}
	
	public function setPw($pw){
		$this->pw = $pw;	
	}
	
	private function getPw($pw){
		return $this->pw;
	}
	
}





