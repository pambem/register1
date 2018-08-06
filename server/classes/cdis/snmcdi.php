<?php

Class SNMCDI extends ConnectionDataInterface {
	
	public function __construct(){
		
		$this->setDB('snm');
		//if($_SERVER['SERVER_NAME'] == 'jooobz.local'){
			$this->setHost('localhost');
			$this->setUser('root');
			$this->setPw('');
		//} else {
		//	$this->setHost('rds.cajnmm3oc9gw.us-east-1.rds.amazonaws.com');
		//	$this->setUser('awsuser');
		//	$this->setPw('');
		//}
		$this->setDBType('mysql');
		
	}
	
	
}

