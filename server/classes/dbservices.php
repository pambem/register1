<?php


Class DBServices {
	
	private $_db; 
	private $cdi;
	private $_mysqli;
	
	public function __construct(ConnectionDataInterface $cdi){
		
		$this->setCDI($cdi);
		
	}
	
	public function connect(){
		
		$c = $this->getCDI();
		$dbtype = $c->getDBType();
		/*
		 * for now we only implement mysql database.
		 */
		switch($dbtype){
			case 'mysql':
				
				//$this->_mysqli = new mysqli("p:" + $c->getHost(), $c->getUser(), $c->getPw(), $c->getDB() );
				$this->_mysqli = new mysqli($c->getHost(), $c->getUser(), $c->getPw(), $c->getDB() );
				if ($this->_mysqli->connect_errno) {
		    		throw new Exception("Failed to connect to MySQL (". $c->getHost() . "," . $c->getUser() . "," . $c->getDb() ." ): " . $this->_mysqli->connect_error);
				}
				return $this->_mysqli;
				
			break;
			default:
				throw new Exception("Error Processing Request. connect() doesn't have the requested method.", 1);		
		}
		
	}
	
	
	
	protected function setCDI(ConnectionDataInterface $cdi){
		$this->cdi  = $cdi;
	}
	
	private function getCDI(){
		return $this->cdi;
	}
	
	public function __destruct(){
		//$this->_mysqli->close();
	}
	
}

/*
$jcdi = new JooobzCDI();
$db = new DBServices($jcdi);
$conn = $db->connect();
$result = $conn->query($sql);
  
*/
